<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $currentUser = Auth::user();
        $baseUrl = url('/');

        return [
          'id' => $this->id,
          'firstname' => $this->firstname,
          'lastname' => $this->lastname,
          'type' => $this->type,
          'calenderToken' => $currentUser->id !== $this->id ? null : $this->calender_token,
          'picture' => $this->picture !== null ? $this->picture : $baseUrl . '/avatar.png',
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
