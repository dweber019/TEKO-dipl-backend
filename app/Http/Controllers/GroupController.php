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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return GroupResource::collection(Group::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(Request $request)
    {
        $this->authorize('create', Group::class);

        $attributes = $request->validate([
          'name' => 'required|string',
        ]);

        $group = tap(new Group($attributes))->save();

        return (new GroupResource($group));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Resources\Json\Resource
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
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);

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
        $this->authorize('delete', $group);

        $group->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\JsonResponse
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
        $this->authorize('addUser', $group);

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
        $this->authorize('removeUser', $group);

        $group->users()->detach($user);
        return response('', Response::HTTP_NO_CONTENT);
    }
}
