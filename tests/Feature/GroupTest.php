<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    public function test_list_groups()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/groups');

        $response->assertSuccessful();

        $response->assertJson([
          [ 'name' => '1A' ]
        ]);
    }

    public function test_delete_group()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->deleteJson('/api/groups/1');

        $response->assertSuccessful();
    }

    public function test_udpate_group_as_teacher()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->putJson('/api/groups/1', [
          'name' => 'Update'
        ]);

        $response->assertStatus(403);
    }

    public function test_udpate_group_as_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->putJson('/api/groups/1', [
          'name' => 'Update'
        ]);

        $response->assertStatus(403);
    }
}
