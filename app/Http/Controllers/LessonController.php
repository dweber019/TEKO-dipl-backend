<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Lesson::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        return $lesson;
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
            Rule::in(['lesson', 'exam', 'reminder']),
          ],
          'location' => 'string|nullable',
          'room' => 'string|nullable',
          'canceled' => 'required|boolean',
        ]);

        $lesson = tap($lesson->fill($attributes))->save();

        return $lesson;
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
        return $lesson->tasks()->get();
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

        return $task;
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
        return $note;
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

        return $note;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function commentsIndex(Lesson $lesson)
    {
        return $lesson->comments()->get();
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

        return $comment;
    }


}
