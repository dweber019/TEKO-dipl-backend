<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Task extends Resource
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
          'name' => $this->name,
          'description' => $this->description,
          'dueDate' => is_null($this->due_date) ? null : $this->due_date->toDateTimeString(),
          'lessonId' => $this->lesson_id,
          'status' => $this->status,
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
