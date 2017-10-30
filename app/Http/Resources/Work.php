<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Work extends Resource
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
          'user' => trim($this->firstname . ' ' . $this->lastname),
          'result' => $this->whenPivotLoaded('task_item_user', function () {
              return $this->pivot->result;
          }),
          'createdAt' => $this->whenPivotLoaded('task_item_user', function () {
              return is_null($this->pivot->created_at) ? null : $this->pivot->created_at->toDateTimeString();
          }),
          'updatedAt' => $this->whenPivotLoaded('task_item_user', function () {
              return is_null($this->pivot->updated_at) ? null : $this->pivot->updated_at->toDateTimeString();
          }),
        ];
    }
}
