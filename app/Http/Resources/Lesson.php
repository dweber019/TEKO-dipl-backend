<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Lesson extends Resource
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
          'startDate' => is_null($this->start_date) ? null : $this->start_date->toDateTimeString(),
          'endDate' => is_null($this->end_date) ? null : $this->end_date->toDateTimeString(),
          'type' => $this->type,
          'location' => $this->location,
          'room' => $this->room,
          'canceled' => !!$this->canceled,
          'subjectId' => $this->subject_id,
          'status' => $this->status,
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
