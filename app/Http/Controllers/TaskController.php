<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Note;
use App\Models\Task;
use App\Models\TaskItem;
use App\Repository\StatusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Task as TaskResource;
use App\Http\Resources\Note as NoteResource;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\TaskItem as TaskItemResource;

class TaskController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
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
        $attributes = $request->validate([
          'name' => 'required|string',
          'description' => 'string|nullable',
          'due_date' => 'required|date|after:now',
        ]);

        $task = tap($task->fill($attributes))->save();

        return redirect('api/tasks/' . $task->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function taskItemsIndex(Task $task)
    {
        return TaskItemResource::collection($task->taskItems()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function taskItemsStore(Request $request, Task $task)
    {
        $attributes = $request->validate([
          'title' => 'required|string',
          'description' => 'string|nullable',
          'question_type' => [
            'required',
            Rule::in(['toggle', 'select', 'file', 'input', 'text']),
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function noteIndex(Request $request, Task $task)
    {
        $currentUser = $request->user();
        $note = $task->notes()->where('user_id', $currentUser->id)->first();
        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function noteUpdate(Request $request, Task $task)
    {
        $attributes = $request->validate([
          'note' => 'required|string',
        ]);

        $currentUser = $request->user();
        $note = $task->notes()->where('user_id', $currentUser->id)->first();

        if ($note === null) {
            $note = new Note($attributes);
            $task->notes()->save($note);
        } else {
            $note = tap($note->fill($attributes))->update();
        }

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function commentsIndex(Task $task)
    {
        return CommentResource::collection($task->comments()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function commentsStore(Request $request, Task $task)
    {
        $attributes = $request->validate([
          'message' => 'required|string',
        ]);

        $attributes['user_id'] = $request->user()->id;

        $comment = new Comment($attributes);
        $task->comments()->save($comment);

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
        $currentUser = $request->user();

        $task->users()->attach($currentUser->id, [ 'done' => true ]);

        return response('', Response::HTTP_OK);
    }
}
