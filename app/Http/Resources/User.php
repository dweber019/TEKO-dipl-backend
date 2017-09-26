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

        return [
          'id' => $this->id,
          'firstname' => $this->fistname,
          'lastname' => $this->lastname,
          'type' => $this->type,
          'calenderToken' => $currentUser->id !== $this->id ? null : $this->calender_token,
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
