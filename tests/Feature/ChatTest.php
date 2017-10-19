<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ChatTest extends TestCase
{
    public function test_list_chat_of_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/users/2/chats');

        $response->assertStatus(200);
    }

    public function test_list_mark_chat_as_read()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/users/2/chats/1/read', []);

        $response->assertSuccessful();
    }

    public function test_create_chat()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/users/2/chats', [
          'senderId' => 2,
          'receiverId' => 1,
          'message' => 'Hallo Benutzer 1',
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('chats', [
          'message' => 'Hallo Benutzer 1'
        ]);
    }

    public function test_delete_chat()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->deleteJson('/api/users/2/chats/1');

        $response->assertSuccessful();
    }
}
