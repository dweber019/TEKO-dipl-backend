<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TaskItem extends Resource
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
          'title' => $this->title,
          'description' => $this->description,
          'questionType' => $this->question_type,
          'question' => $this->question,
          'order' => $this->order,
          'taskId' => $this->task_id,
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
