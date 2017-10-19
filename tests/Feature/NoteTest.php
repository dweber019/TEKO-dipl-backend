<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class NoteTest extends TestCase
{
    public function test_get_note_for_lesson()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/lessons/1/note');

        $response->assertSuccessful();
    }

    public function test_change_note_for_lesson()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->put('/api/lessons/1/note', [
          'note' => 'Update user 2'
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('notes', [
          'note' => 'Update user 2'
        ]);
    }

    public function test_get_note_for_task()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/tasks/1/note');

        $response->assertSuccessful();
    }

    public function test_change_note_for_task()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->put('/api/tasks/1/note', [
          'note' => 'Update user 2'
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('notes', [
          'note' => 'Update user 2'
        ]);
    }
}
