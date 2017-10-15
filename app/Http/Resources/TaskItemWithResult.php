<?php

namespace App\Http\Resources;

use Auth0\Login\Auth0JWTUser;
use Auth0\Login\facade\Auth0;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Request;

class TaskItemWithResult extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $users = [];
        foreach ($this->whenLoaded('users') as $user) {
            $result = $user->pivot->result;

            if ($this->question_type === \App\Helpers\QuestionTypes::FILE) {
                $result = $baseUrl = url(
                    '/'
                  ) . '/api/taskItems/' . $this->id . '/file?api_token=' . substr($request->headers->get('authorization'), 7);
            }

            array_push($users, [
              'user' => new User($user),
              'result' => $result
            ]);
        }

        return [
          'id' => $this->id,
          'title' => $this->title,
          'description' => $this->description,
          'questionType' => $this->question_type,
          'question' => $this->question,
          'order' => $this->order,
          'taskId' => $this->task_id,
          'users' => $users,
          'createdAt' => is_null($this->created_at) ? null : $this->created_at->toDateTimeString(),
          'updatedAt' => is_null($this->updated_at) ? null : $this->updated_at->toDateTimeString(),
        ];
    }
}
