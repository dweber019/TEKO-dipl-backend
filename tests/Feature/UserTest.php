<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_list_users()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/users');

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'firstname' => $actingUser->firstname ]);
    }

    public function test_delete_user()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->deleteJson('/api/users/4');

        $response->assertSuccessful();
    }

    public function test_udpate_own_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->put('/api/users/2', [
          'firstname' => 'Hans',
          'lastname' => 'Müller',
          'type' => 'student',
        ]);

        $response->assertSuccessful();
    }

    public function test_udpate_not_own_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->put('/api/users/4', [
          'firstname' => 'Hans',
          'lastname' => 'Müller',
          'type' => 'student',
        ]);

        $response->assertStatus(403);
    }

    public function test_udpate_own_users_disallowed_field()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->put('/api/users/2', [
          'firstname' => 'Hans',
          'lastname' => 'Müller',
          'type' => 'teacher',
        ]);

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'type' => 'student' ]);
    }
}
