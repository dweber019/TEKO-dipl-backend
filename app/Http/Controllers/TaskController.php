<?php

namespace App\Http\Controllers;

use App\Helpers\QuestionTypes;
use App\Models\Comment;
use App\Models\Note;
use App\Models\Task;
use App\Models\TaskItem;
use App\Policies\TaskPolicy;
use App\Repository\NotificationRepository;
use App\Repository\StatusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Task as TaskResource;
use App\Http\Resources\Note as NoteResource;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\TaskItem as TaskItemResource;
use App\Http\Resources\TaskItemWithResult as TaskItemWithResultResource;

class TaskController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return new TaskResource($task);
        }

        $taskWithRelation = $task->load([ 'users' => function ($query) use ($currentUser) {
            $query->where('user_id', '=', $currentUser->id);
        } ]);

        $taskWithStatus = StatusRepository::getStatusOfTask($taskWithRelation);

        return new TaskResource($taskWithStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('isTeacher', $task);

        $attributes = $request->validate([
          'name' => 'required|string',
          'description' => 'string|nullable',
          'due_date' => 'required|date|after:now',
        ]);

        $task = tap($task->fill($attributes))->save();

        return $this->show($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $this->authorize('isTeacher', $task);

        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskItemsIndex(Task $task)
    {
        $this->authorize('view', $task);

        $currentUser = Auth::user();

        if (TaskPolicy::isUserTeacher($currentUser, $task) || $currentUser->isAdmin()) {
            $taskItems = $task->taskItems()->with('users')->get();
        } else {
            $taskItems = $task->taskItems()->with(['users' => function ($query) use ($currentUser) {
                $query->where('user_id', '=', $currentUser->id);
            }])->get();
        }


        return TaskItemWithResultResource::collection($taskItems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function taskItemsStore(Request $request, Task $task)
    {
        $this->authorize('isTeacher', $task);

        $attributes = $request->validate([
          'title' => 'required|string',
          'description' => 'string|nullable',
          'question_type' => [
            'required',
            Rule::in(QuestionTypes::toArray()),
          ],
          'question' => 'string|nullable',
          'order' => 'integer',
        ]);

        $attributes['task_id'] = $task->id;

        $taskItem = tap(new TaskItem($attributes))->save();

        return new TaskItemResource($taskItem);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function noteIndex(Task $task)
    {
        $this->authorize('view', $task);

        $currentUser = Auth::user();
        $note = $task->notes()->where('user_id', $currentUser->id)->first();

        if ($note === null) {
            $note = new Note([ 'note' => '', 'user_id' => $currentUser->id ]);
            $task->notes()->save($note);
        }

        $note = $task->notes()->where('user_id', $currentUser->id)->first();

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function noteUpdate(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $attributes = $request->validate([
          'note' => 'required|string',
        ]);

        $currentUser = $request->user();
        $note = $task->notes()->where('user_id', $currentUser->id)->first();

        if ($note === null) {
            $note = new Note($attributes);
            $task->notes()->save($note);
        } else {
            $note->fill($attributes)->update();
        }

        $note = $task->notes()->where('user_id', $currentUser->id)->first();

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function commentsIndex(Task $task)
    {
        $this->authorize('view', $task);

        return CommentResource::collection($task->comments()->with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function commentsStore(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $attributes = $request->validate([
          'message' => 'required|string',
        ]);

        $attributes['user_id'] = $request->user()->id;

        $comment = new Comment($attributes);
        $task->comments()->save($comment);

        NotificationRepository::taskCommentAdded($task, $request->user());

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function doneUpdate(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $currentUser = $request->user();

        $task->users()->attach($currentUser->id, [ 'done' => true ]);

        return response('', Response::HTTP_OK);
    }
}
