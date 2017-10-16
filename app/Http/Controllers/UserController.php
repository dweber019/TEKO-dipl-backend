<?php

namespace App\Http\Controllers;

use App\Helpers\UserTypes;
use App\Mail\UserInvitation;
use App\Main;
use App\Models\Chat;
use App\Models\Notification;
use App\Models\User;
use App\Repository\NotificationRepository;
use App\Repository\StatusRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Group as GroupResource;
use App\Http\Resources\Notification as NotificationResource;
use App\Http\Resources\Grade as GradeResource;
use App\Http\Resources\Chat as ChatResource;
use App\Http\Resources\Subject as SubjectResource;
use App\Http\Resources\Agenda as AgendaResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $attributes = $request->validate([
          'firstname' => 'string|nullable',
          'lastname' => 'string|nullable',
          'invite_email' => 'required|email|unique:users',
          'type' => [
            'required',
            Rule::in(UserTypes::toArray()),
          ],
        ]);

        $user = tap(new User($attributes))->save();

        $invitation = new \App\Helpers\UserInvitation($user->firstname, $user->lastname);
        Mail::to($user->invite_email)->send(new UserInvitation($invitation));

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $attributes = $request->validate([
          'firstname' => 'string',
          'lastname' => 'string',
          'type' => [
            'required',
            Rule::in(UserTypes::toArray()),
          ],
        ]);

        if (!Auth::user()->isAdmin()) {
            unset($attributes['type']);
        }

        $user = tap($user->fill($attributes))->save();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the token user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function meIndex()
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    /**
     * Display the groups of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupsIndex(User $user)
    {
        $this->authorize('view', $user);

        return GroupResource::collection($user->groups()->get());
    }

    /**
     * Display the subjects of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\jsonResponse
     */
    public function subjectsIndex(User $user)
    {
        $currentUser = Auth::user();

        $this->authorize('self', $user);

        if ($currentUser->isTeacher()) {
            $subjects = $user->subjectsAsTeacher()->with('teacher')->get();

            return SubjectResource::collection($subjects);
        }

        $subjects = $user->subjects()->with([ 'lessons.tasks.users' => function ($query) use ($user) {
            $query->where('user_id', '=', $user->id);
        }, 'teacher' ])->get();

        $subjectWithStatus = StatusRepository::getStatusOfSubjects($subjects);

        return SubjectResource::collection($subjectWithStatus);
    }

    /**
     * Display the notifications of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationsIndex(User $user)
    {
        $this->authorize('self', $user);

        return NotificationResource::collection($user->notifications()->get());
    }
    public function notificationsMeIndex()
    {
        return $this->notificationsIndex(Auth::user());
    }

    /**
     * Mark the notifications of the specified resource as read.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationsRead(User $user, Notification $notification)
    {
        $this->authorize('self', $user);

        $user->notifications()->updateExistingPivot($notification->id, [ 'read' => true ]);

        return response('', Response::HTTP_OK);
    }
    public function notificationsMeRead(Notification $notification)
    {
        return $this->notificationsRead(Auth::user(), $notification);
    }

    /**
     * Display the grades of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function gradesIndex(User $user)
    {
        $this->authorize('self', $user);

        return GradeResource::collection($user->grades()->with('teacher')->get());
    }
    public function gradesMeIndex()
    {
        return $this->gradesIndex(Auth::user());
    }

    /**
     * Display the agenda of the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function agendaIndex()
    {
        /* @var $currentUser User */
        $currentUser = Auth::user();

        $subjects = $currentUser->subjects()->with([ 'lessons' => function ($query) use ($currentUser) {
            $query->where('start_date', '>=', Carbon::today()->toDateTimeString());
            $query->with([ 'tasks.users' => function ($query) use ($currentUser) {
                $query->where('user_id', '=', $currentUser->id);
            } , 'subject']);
        } ])->get();

        $lessons = collect($subjects)->map(function($item) {
            return $item->lessons;
        })->flatten(1);

        $agenda = StatusRepository::getStatusOfLessons($lessons);

        return AgendaResource::collection($agenda);
    }

    /**
     * Display the chats of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function chatsIndex(User $user)
    {
        $this->authorize('self', $user);

        $asSender = $user->senderChat()->with(['sender', 'receiver'])->get();
        $asReceiver = $user->receiverChat()->with(['sender', 'receiver'])->get();

        return ChatResource::collection(collect($asSender)->merge($asReceiver));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function chatsStore(Request $request, User $user)
    {
        $this->authorize('self', $user);

        $attributes = $request->validate([
          'message' => 'required|string',
          'sender_id' => 'required|integer|exists:users,id',
          'receiver_id' => 'required|integer|exists:users,id',
        ]);

        $attributes['sender_id'] = $user->id;

        $chat = tap(new Chat($attributes))->save();

        NotificationRepository::chatAdded($user, $attributes['receiver_id']);

        return new ChatResource($chat);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user2
     * @return \Illuminate\Http\Response
     */
    public function chatsDestroy(User $user, User $user2)
    {
        $this->authorize('self', $user);

        DB::table('chats')
          ->where([
            ['sender_id', '=', $user->id],
            ['receiver_id', '=', $user2->id],
          ])
          ->orWhere([
            ['sender_id', '=', $user2->id],
            ['receiver_id', '=', $user->id],
          ])
          ->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user2
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function chatsRead(User $user, User $user2)
    {
        DB::table('chats')
          ->where([
            ['sender_id', '=', $user->id],
            ['receiver_id', '=', $user2->id],
          ])
          ->orWhere([
            ['sender_id', '=', $user2->id],
            ['receiver_id', '=', $user->id],
          ])
          ->update(['read' => true]);

        return response('', Response::HTTP_OK);
    }

    /**
     * Display the chats of the specified resource.
     *
     * @param  String  $token
     * @return \Illuminate\Http\Response
     */
    public function feedIndex(String $token)
    {
        /* @var $currentUser User */
        $currentUser = User::where('calender_token', $token)->first();

        $subjects = $currentUser->subjects()->with([ 'lessons.subject' ])->get();

        $lessons = collect($subjects)->map(function($item) {
            return $item->lessons;
        })->flatten(1);

        $vCalendar = new \Eluceo\iCal\Component\Calendar('dipl-backend.scapp.io');

        foreach ($lessons->toArray() as &$lesson) {
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent
              ->setDtStart(new \DateTime($lesson['start_date'], new \DateTimeZone(config('app.timezone'))))
              ->setDtEnd(new \DateTime($lesson['end_date'], new \DateTimeZone(config('app.timezone'))))
              ->setLocation($lesson['location'])
              ->setSummary($lesson['subject']['name'])
              ->setDescription('Room: ' . $lesson['room']);

            $vCalendar->addComponent($vEvent);
        }

        return response($vCalendar->render(), 200, [
          'Content-Type' => 'text/calendar; charset=utf-8',
          'Content-Disposition' => 'attachment; filename="cal.ics"',
        ]);
    }

}
