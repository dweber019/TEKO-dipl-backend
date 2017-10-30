<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserGroupTest extends TestCase
{
    public function test_add_user_to_group_as_teacher()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->postJson('/api/groups/1/users/3', []);

        $response->assertSuccessful();
    }

    public function test_remove_user_to_group_as_teacher()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->deleteJson('/api/groups/1/users/3');

        $response->assertSuccessful();
    }

    public function test_add_user_to_group_as_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/groups/1/users/3', []);

        $response->assertStatus(403);
    }

    public function test_remove_user_to_group_as_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->deleteJson('/api/groups/1/users/3');

        $response->assertStatus(403);
    }
}
