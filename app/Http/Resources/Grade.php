<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Grade extends Resource
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
          'archived' => !!$this->archived,
          'teacher' => $this->teacher,
          'teacherId' => $this->teacher_id,
          'grade' => $this->whenPivotLoaded('grades', function () {
              return $this->pivot->grade;
          }),
          'gradeDate' => $this->whenPivotLoaded('grades', function () {
              return is_null($this->pivot->created_at) ? null : $this->pivot->created_at->toDateTimeString();
          }),
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
