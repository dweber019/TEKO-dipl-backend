<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SubjectTest extends TestCase
{

    private $subjectPost = [
      'name' => 'New Subject',
      'archived' => false,
      'teacherId' => '22',
    ];

    public function test_list_subjects_admin()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->getJson('/api/subjects');

        $response->assertSuccessful();

        $response->assertJson([
          [ 'name' => 'Englisch' ]
        ]);
    }

    public function test_list_subjects_redirect_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/subjects');

        $response->assertRedirect('api/users/2/subjects');
    }

    public function test_list_subjects_redirect_teacher()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->getJson('/api/subjects');

        $response->assertRedirect('api/users/22/subjects');
    }

    public function test_teacher_can_create_subject()
    {
        $actingUser = User::find(22);

        $response = $this->actingAs($actingUser)->postJson('/api/subjects', $this->subjectPost);

        $response->assertSuccessful();

        $response->assertJson([ 'name' => $this->subjectPost['name'] ]);
    }

    public function test_user_can_not_create_subject()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/subjects', $this->subjectPost);

        $response->assertStatus(403);
    }

    public function test_can_delete_subject()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->deleteJson('/api/subjects/1');

        $response->assertSuccessful();
    }

    public function test_can_update_subject()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->putJson('/api/subjects/1', $this->subjectPost);

        $response->assertSuccessful();

        $response->assertJson([ 'name' => $this->subjectPost['name'] ]);
    }
}
