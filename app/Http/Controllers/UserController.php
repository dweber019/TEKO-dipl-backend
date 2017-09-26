<?php

namespace App\Http\Controllers;

use App\Helpers\UserTypes;
use App\Models\Chat;
use App\Models\User;
use App\Repository\StatusRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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
          'firstname' => 'string|nullable',
          'lastname' => 'string|nullable',
          'invite_email' => 'required|email|unique:users',
          'type' => [
            'required',
            Rule::in([UserTypes::STUDENT, UserTypes::TEACHER, UserTypes::ADMIN]),
          ],
        ]);

        $user = tap(new User($attributes))->save();

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $attributes = $request->validate([
          'firstname' => 'string',
          'lastname' => 'string',
          'type' => [
            'required',
            Rule::in([UserTypes::STUDENT, UserTypes::TEACHER, UserTypes::ADMIN]),
          ],
        ]);

        $user = tap($user->fill($attributes))->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the groups of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function groupsIndex(User $user)
    {
        return $user->groups()->get();
    }

    /**
     * Display the subjects of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function subjectsIndex(User $user)
    {
        $subjects = $user->subjects()->with([ 'lessons.tasks.users' => function ($query) use ($user) {
            $query->where('user_id', '=', $user->id);
        } ])->get();

        $subjectWithStatus = StatusRepository::getStatusOfSubjects($subjects);

        return $subjectWithStatus;
    }

    /**
     * Display the notifications of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function notificationsIndex(User $user)
    {
        return $user->notifications()->get();
    }

    /**
     * Display the grades of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function gradesIndex(User $user)
    {
        return $user->grades()->get();
    }

    /**
     * Display the agenda of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function agendaIndex(Request $request)
    {
        /* @var $currentUser User */
        $currentUser = $request->user();

        $subjects = $currentUser->subjects()->with([ 'lessons' => function ($query) use ($currentUser) {
            $query->where('start_date', '>=', Carbon::today()->toDateTimeString());
            $query->with([ 'tasks.users' => function ($query) use ($currentUser) {
                $query->where('user_id', '=', $currentUser->id);
            } ]);
        } ])->get();

        $lessons = collect($subjects->toArray())->map(function($item) {
            return $item['lessons'];
        })->flatten(1);

        $agenda = StatusRepository::getStatusOfLessons($lessons);

        return $agenda;
    }

    /**
     * Display the chats of the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function chatsIndex(User $user)
    {
        $asSender = $user->senderChat()->get();
        $asReceiver = $user->receiverChat()->get();

        return array_merge($asSender, $asReceiver);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function chatsStore(Request $request, User $user)
    {
        $attributes = $request->validate([
          'message' => 'required|string',
          'sender_id' => 'required|integer|exists:users',
          'receiver_id' => 'required|integer|exists:users',
        ]);

        $chat = tap(new Chat($attributes))->save();

        return $chat;
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
     * Display the chats of the specified resource.
     *
     * @param  String  $token
     * @return \Illuminate\Http\Response
     */
    public function feedIndex(String $token)
    {
        // TODO: Implement Feed
    }

}
