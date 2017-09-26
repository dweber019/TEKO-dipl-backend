<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Group as GroupResource;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GroupResource::collection(Group::all());
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
        ]);

        $group = tap(new Group($attributes))->save();

        return (new GroupResource($group))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $attributes = $request->validate([
          'name' => 'required|string',
        ]);

        $group = tap($group->fill($attributes))->save();

        return new GroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function usersIndex(Group $group)
    {
        return UserResource::collection($group->users()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Group  $group
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function usersStore(Group $group, User $user)
    {
        $group->users()->attach($user);
        return response('', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function usersDestroy(Group $group, User $user)
    {
        $group->users()->detach($user);
        return response('', Response::HTTP_NO_CONTENT);
    }
}
