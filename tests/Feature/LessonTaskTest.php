<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class LessonTaskTest extends TestCase
{

    private $taskPost = [
      'name' => 'New Task',
      'description' => 'Some Description text',
      'due_date' => '',
    ];

    public function test_list_tasks()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->getJson('/api/lessons/1/tasks');

        $response->assertSuccessful();

        $response->assertJson([
          [ 'id' => 1, 'name' => 'Übersetzten' ],
        ]);
    }

    public function test_create_a_task_as_teacher()
    {
        $actingUser = User::find(22);

        $taskPost = $this->taskPost;
        $taskPost['due_date'] = Carbon::now()->addDay(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->postJson('/api/lessons/1/tasks', $taskPost);

        $response->assertStatus(302);
    }

    public function test_create_a_task_as_user()
    {
        $actingUser = User::find(2);

        $taskPost = $this->taskPost;
        $taskPost['due_date'] = Carbon::now()->addDay(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->postJson('/api/lessons/1/tasks', $taskPost);

        $response->assertStatus(403);
    }

    public function test_create_a_task_with_wrong_date()
    {
        $actingUser = User::find(22);

        $taskPost = $this->taskPost;
        $taskPost['due_date'] = Carbon::now()->subDay(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->postJson('/api/lessons/1/tasks', $taskPost);

        $response->assertStatus(422);
    }
}
