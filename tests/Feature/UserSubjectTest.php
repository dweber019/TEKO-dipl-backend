<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserSubjectTest extends TestCase
{
    public function test_list_subject_of_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/users/2/subjects');

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'name' => 'Englisch' ]);
    }

    public function test_list_subject_of_teacher()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->getJson('/api/users/22/subjects');

        $response->assertSuccessful();

        $response->assertJsonFragment([ 'name' => 'Englisch' ]);
    }

    public function test_add_user_to_subject()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/users/14', []);

        $response->assertSuccessful();
    }

    public function test_remove_user_from_subject()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->deleteJson('/api/subjects/1/users/3');

        $response->assertSuccessful();
    }

    public function test_add_user_to_subject_as_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/users/14', []);

        $response->assertStatus(403);
    }
}
