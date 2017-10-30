<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_add_comment_to_lesson()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/lessons/1/comments', [
          'message' => 'Hallo comments',
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('comments', [
          'message' => 'Hallo comments'
        ]);
    }

    public function test_add_comment_to_task()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/tasks/1/comments', [
          'message' => 'Hallo comments',
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('comments', [
          'message' => 'Hallo comments'
        ]);
    }
}
