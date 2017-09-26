<?php

namespace App\Http\Controllers;

use App\Helpers\LessonType;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\Note;
use App\Models\Task;
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
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return $lesson;
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
        $attributes = $request->validate([
          'start_date' => 'required|date|after:now',
          'end_date' => 'required|date|after:start_date',
          'type' => [
            'required',
            Rule::in([LessonType::LESSON, LessonType::EXAM, LessonType::REMINDER]),
          ],
          'location' => 'string|nullable',
          'room' => 'string|nullable',
          'canceled' => 'required|boolean',
        ]);

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
        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return $lesson->tasks()->get();
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
     * @return \Illuminate\Http\Response
     */
    public function noteIndex(Request $request, Lesson $lesson)
    {
        $currentUser = $request->user();
        $note = $lesson->notes()->where('user_id', $currentUser->id)->first();
        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function noteUpdate(Request $request, Lesson $lesson)
    {
        $attributes = $request->validate([
          'note' => 'required|string',
        ]);

        $currentUser = $request->user();
        $note = $lesson->notes()->where('user_id', $currentUser->id)->first();

        if ($note === null) {
            $note = tap(new Note($attributes))->save();
            $lesson->notes()->save($note);
        } else {
            $note = tap($note->fill($attributes))->update();
        }

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function commentsIndex(Lesson $lesson)
    {
        return CommentResource::collection($lesson->comments()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function commentsStore(Request $request, Lesson $lesson)
    {
        $attributes = $request->validate([
          'message' => 'required|string',
        ]);

        $attributes['user_id'] = $request->user()->id;

        $comment = new Comment($attributes);
        $lesson->comments()->save($comment);

        return new CommentResource($comment);
    }


}
