<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Group::all();
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

        return $group;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $group;
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

        return $group;
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
        return $group->users()->get();
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
