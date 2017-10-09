<?php

namespace App\Http\Controllers;

use App\Helpers\LessonTypes;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\User;
use App\Repository\NotificationRepository;
use App\Repository\StatusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Subject as SubjectResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\GradeUser as GradeUserResource;
use App\Http\Resources\Lesson as LessonResource;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $currentUser = $request->user();

        if ($currentUser->isNotStudent()) {
            return SubjectResource::collection(Subject::with('teacher')->all());
        }

        return redirect('api/users/' . $currentUser->id . '/subjects');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Subject::class);

        $attributes = $request->validate([
          'name' => 'required|string',
          'archived' => 'required|boolean',
          'teacher_id' => 'required|integer|exists:users,id',
        ]);

        $subject = tap(new Subject($attributes))->save();

        return redirect('api/subjects/' . $subject->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(Subject $subject)
    {
        $this->authorize('view', $subject);

        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return new SubjectResource($subject);
        }

        $subjectWithRelation = $subject->load([ 'lessons.tasks.users' => function ($query) use ($currentUser) {
            $query->where('user_id', '=', $currentUser->id);
        } ]);

        $subjectWithStatus = StatusRepository::getStatusOfSubject($subjectWithRelation);

        return new SubjectResource($subjectWithStatus);
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
        $this->authorize('update', $subject);

        $attributes = $request->validate([
          'name' => 'required|string',
          'archived' => 'required|boolean',
          'teacher_id' => 'required|integer|exists:users,id',
        ]);

        $subject = tap($subject->fill($attributes))->save();

        return redirect('api/subjects/' . $subject->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $this->authorize('delete', $subject);

        $subject->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\JsonResponse
     */
    public function lessonsIndex(Subject $subject)
    {
        $this->authorize('view', $subject);

        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return LessonResource::collection($subject->lessons()->get());
        }

        $lessons = $subject->lessons()->with([ 'tasks.users' => function ($query) use ($currentUser) {
            $query->where('user_id', '=', $currentUser->id);
        } ])->get();

        $lessonsWithStatus = StatusRepository::getStatusOfLessons($lessons);

        return LessonResource::collection(collect($lessonsWithStatus));
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
        $this->authorize('isTeacher', $subject);

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

        $attributes['subject_id'] = $subject->id;

        $lesson = tap(new Lesson($attributes))->save();

        NotificationRepository::lessonAdded($lesson);

        return redirect('api/lessons/' . $lesson->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\JsonResponse
     */
    public function gradesIndex(Subject $subject)
    {
        $this->authorize('view', $subject);

        $currentUser = Auth::user();

        if ($currentUser->isNotStudent()) {
            return GradeUserResource::collection($subject->userGrades()->get());
        }

        return GradeUserResource::collection($subject->userGrades()->where('user_id', $currentUser->id)->get());
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
        $this->authorize('isTeacher', $subject);

        $attributes = $request->validate([
          'grade' => 'required|numeric',
        ]);

        $subject->userGrades()->attach($user->id, $attributes);

        NotificationRepository::gradeAdded($subject, $user);

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
        $this->authorize('isTeacher', $subject);

        $subject->userGrades()->detach($user->id);

        NotificationRepository::gradeRemoved($subject, $user);

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\JsonResponse
     */
    public function usersIndex(Subject $subject)
    {
        $this->authorize('view', $subject);

        return UserResource::collection($subject->users()->get());
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
        $this->authorize('isTeacher', $subject);

        $subject->users()->attach($user->id);

        NotificationRepository::userAddedToSubject($subject, $user);

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
        $this->authorize('isTeacher', $subject);

        $subject->users()->detach($user->id);

        NotificationRepository::userRemovedToSubject($subject, $user);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
