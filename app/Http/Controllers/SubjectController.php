<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Subject::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
          'name' => 'required|string',
          'archived' => 'required|boolean',
          'teacher_id' => 'required|integer|exists:users,id',
        ]);

        $subject = tap(new Subject($attributes))->save();

        return $subject;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        return $subject;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $attributes = $request->validate([
          'name' => 'required|string',
          'archived' => 'required|boolean',
          'teacher_id' => 'required|integer|exists:users,id',
        ]);

        $subject = tap($subject->fill($attributes))->save();

        return $subject;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function lessonsIndex(Subject $subject)
    {
        return $subject->lessons()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function lessonsStore(Request $request, Subject $subject)
    {
        $attributes = $request->validate([
          'start_date' => 'required|date|after:now',
          'end_date' => 'required|date|after:start_date',
          'location' => 'string|nullable',
          'room' => 'string|nullable',
          'canceled' => 'required|boolean',
        ]);

        $attributes['subject_id'] = $subject->id;

        $lesson = tap(new Lesson($attributes))->save();

        return $lesson;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function gradesIndex(Subject $subject)
    {
        return $subject->userGrades()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function gradesStore(Request $request, Subject $subject, User $user)
    {
        $attributes = $request->validate([
          'grade' => 'required|numeric',
        ]);

        $subject->userGrades()->attach($user->id, $attributes);
        return response('', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function gradesDestroy(Subject $subject, User $user)
    {
        $subject->userGrades()->detach($user->id);
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function usersIndex(Subject $subject)
    {
        return $subject->users()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function usersStore(Request $request, Subject $subject, User $user)
    {
        $subject->users()->attach($user->id);
        return response('', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function usersDestroy(Subject $subject, User $user)
    {
        $subject->users()->detach($user->id);
        return response('', Response::HTTP_NO_CONTENT);
    }
}
