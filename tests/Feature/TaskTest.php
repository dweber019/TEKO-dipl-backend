<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class TaskTest extends TestCase
{

    private $taskPost = [
      'name' => 'New Task',
      'description' => 'Some Description text',
      'due_date' => '',
    ];

    public function test_get_task_as_wrong_user()
    {
        $actingUser = User::find(15);

        $response = $this->actingAs($actingUser)->getJson('/api/tasks/1');

        $response->assertStatus(403);
    }

    public function test_get_task_as_wrong_teacher()
    {
        $actingUser = User::find(23);

        $response = $this->actingAs($actingUser)->getJson('/api/tasks/1');

        $response->assertStatus(403);
    }

    public function test_update_task()
    {
        $actingUser = User::find(1);

        $taskPost = $this->taskPost;
        $taskPost['due_date'] = Carbon::now()->addDay(1)->toDateTimeString();
        $taskPost['name'] = 'Updated';

        $response = $this->actingAs($actingUser)->putJson('/api/tasks/1', $taskPost);

        $response->assertSuccessful();

        $response->assertJson([ 'name' => 'Updated' ]);
    }

    public function test_delete_task()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->deleteJson('/api/tasks/1');

        $response->assertSuccessful();
    }

    public function test_mark_task_as_done()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->putJson('/api/tasks/1/done', []);

        $response->assertSuccessful();
    }
}
