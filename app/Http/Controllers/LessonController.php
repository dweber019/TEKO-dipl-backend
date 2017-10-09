<?php

namespace App\Http\Controllers;

use App\Helpers\LessonTypes;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\Note;
use App\Models\Task;
use App\Repository\NotificationRepository;
use App\Repository\StatusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Lesson as LessonResource;
use App\Http\Resources\Task as TaskResource;
use App\Http\Resources\Note as NoteResource;
use App\Http\Resources\Comment as CommentResource;

class LessonController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(Lesson $lesson)
    {
        $this->authorize('view', $lesson);

        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return new LessonResource($lesson);
        }

        $lessonWithRelation = $lesson->load([ 'tasks.users' => function ($query) use ($currentUser) {
            $query->where('user_id', '=', $currentUser->id);
        } ]);

        $lessonWithStatus = StatusRepository::getStatusOfLesson($lessonWithRelation);

        return new LessonResource($lessonWithStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('isTeacher', $lesson);

        $attributes = $request->validate([
          'start_date' => 'required|date|after:now',
          'end_date' => 'required|date|after:start_date',
          'type' => [
            'required',
            Rule::in(LessonTypes::toArray()),
          ],
          'location' => 'string|nullable',
          'room' => 'string|nullable',
          'canceled' => 'required|boolean',
        ]);

        if ($lesson->canceled === false && $attributes['canceled'] === true) {
            NotificationRepository::lessonCanceled($lesson);
        }
        if ($lesson->canceled === true && $attributes['canceled'] === false) {
            NotificationRepository::lessonUncanceled($lesson);
        }

        $lesson = tap($lesson->fill($attributes))->save();

        return redirect('api/lessons/' . $lesson->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        $this->authorize('isTeacher', $lesson);

        NotificationRepository::lessonRemoved($lesson);

        $lesson->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function tasksIndex(Lesson $lesson)
    {
        $this->authorize('view', $lesson);

        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return TaskResource::collection($lesson->tasks()->get());
        }

        $tasksWithRelation = $lesson->tasks()->with([ 'users' => function ($query) use ($currentUser) {
            $query->where('user_id', '=', $currentUser->id);
        } ])->get();

        $tasksWithStatus = StatusRepository::getStatusOfTasks($tasksWithRelation);

        return TaskResource::collection($tasksWithStatus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function tasksStore(Request $request, Lesson $lesson)
    {
        $this->authorize('isTeacher', $lesson);

        $attributes = $request->validate([
          'name' => 'required|string',
          'description' => 'string|nullable',
          'due_date' => 'required|date|after:now',
        ]);

        $attributes['lesson_id'] = $lesson->id;

        $task = tap(new Task($attributes))->save();

        return redirect('api/tasks/' . $task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function noteIndex(Request $request, Lesson $lesson)
    {
        $this->authorize('view', $lesson);

        $currentUser = $request->user();
        $note = $lesson->notes()->where('user_id', $currentUser->id)->first();

        if ($note === null) {
            $note = new Note([ 'note' => '', 'user_id' => $currentUser->id ]);
            $lesson->notes()->save($note);
        }

        $note = $lesson->notes()->where('user_id', $currentUser->id)->first();

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function noteUpdate(Request $request, Lesson $lesson)
    {
        $this->authorize('view', $lesson);

        $attributes = $request->validate([
          'note' => 'required|string',
        ]);

        $currentUser = $request->user();
        $note = $lesson->notes()->where('user_id', $currentUser->id)->first();

        if ($note === null) {
            $attributes['user_id'] = $request->user()->id;
            $note = new Note($attributes);
            $lesson->notes()->save($note);
        } else {
            $note->fill($attributes)->update();
        }

        $note = $lesson->notes()->where('user_id', $currentUser->id)->first();

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function commentsIndex(Lesson $lesson)
    {
        $this->authorize('view', $lesson);

        return CommentResource::collection($lesson->comments()->with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function commentsStore(Request $request, Lesson $lesson)
    {
        $this->authorize('view', $lesson);

        $attributes = $request->validate([
          'message' => 'required|string',
        ]);

        $attributes['user_id'] = $request->user()->id;

        $comment = new Comment($attributes);
        $lesson->comments()->save($comment);

        NotificationRepository::lessonCommentAdded($lesson, $request->user());

        return new CommentResource($comment);
    }


}
