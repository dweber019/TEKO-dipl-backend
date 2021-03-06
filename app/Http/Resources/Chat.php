<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\User as UserResource;

class Chat extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'message' => $this->message,
          'read' => !!$this->read,
          'senderId' => $this->sender_id,
          'sender' => new UserResource($this->sender),
          'receiverId' => $this->receiver_id,
          'receiver' => new UserResource($this->receiver),
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
