<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTaskItemTest extends TestCase
{
    private $taskitemPost = [
      'title' => 'New Task Item',
      'description' => 'Task Item description',
      'questionType' => 'input',
      'question' => null,
      'order' => 1,
    ];

    public function test_create_task_item()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->postJson('/api/tasks/1/taskItems', $this->taskitemPost);

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'title' => $this->taskitemPost['title'] ]);
    }

    public function test_list_task_item()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/tasks/1/taskItems');

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'title' => 'do' ]);
    }
}
