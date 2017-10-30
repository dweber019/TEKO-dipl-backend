<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class SubjectLessonTest extends TestCase
{

    private $lessonPost = [
      'start_date' => '',
      'end_date' => '',
      'location' => 'Basel',
      'room' => 'R308',
      'canceled' => false,
      'type' => 'lesson',
    ];

    public function test_list_lessons()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->getJson('/api/subjects/1/lessons');

        $response->assertSuccessful();

        $response->assertJson([
          [ 'id' => 1 ]
        ]);
    }

    public function test_create_a_lessons_as_teacher()
    {
        $actingUser = User::find(22);

        $lessonPost = $this->lessonPost;
        $lessonPost['start_date'] = Carbon::now()->addDay(1)->toDateTimeString();
        $lessonPost['end_date'] = Carbon::now()->addDay(1)->addHour(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/lessons', $lessonPost);

        $response->assertStatus(302);
    }

    public function test_create_a_lessons_as_user()
    {
        $actingUser = User::find(2);

        $lessonPost = $this->lessonPost;
        $lessonPost['start_date'] = Carbon::now()->addDay(1)->toDateTimeString();
        $lessonPost['end_date'] = Carbon::now()->addDay(1)->addHour(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/lessons', $lessonPost);

        $response->assertStatus(403);
    }

    public function test_create_a_lessons_with_wrong_date()
    {
        $actingUser = User::find(22);

        $lessonPost = $this->lessonPost;
        $lessonPost['start_date'] = Carbon::now()->subDay(1)->toDateTimeString();
        $lessonPost['end_date'] = Carbon::now()->subDay(1)->addHour(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->postJson('/api/subjects/1/lessons', $lessonPost);

        $response->assertStatus(422);
    }
}
