<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Notification extends Resource
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
          'ref_id' => $this->ref_id,
          'ref' => $this->ref,
          'read' => $this->whenPivotLoaded('notification_user', function () {
              return !!$this->pivot->read;
          }),
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
