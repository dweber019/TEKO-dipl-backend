<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class GradeTest extends TestCase
{
    public function test_list_grade_of_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/users/2/grades');

        $response->assertStatus(200);

        $response->assertJson([
          [ 'name' => 'Englisch' ]
        ]);
    }

    public function test_list_grade_of_user_in_subject()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/subjects/1/grades');

        $response->assertStatus(200);

        $response->assertJson([
          [ 'firstname' => $actingUser->firstname ]
        ]);
    }

    public function test_add_grade_for_user()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/grades/2', [
          'grade' => 5.5
        ]);

        $response->assertSuccessful();
    }

    public function test_remove_grade_for_user()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->deleteJson('/api/subjects/1/grades/1');

        $response->assertSuccessful();
    }

    public function test_check_grade_create_permission()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/grades/2', [
          'grade' => 5.5
        ]);

        $response->assertStatus(403);
    }
}
