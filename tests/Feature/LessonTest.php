<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class LessonTest extends TestCase
{

    private $lessonPost = [
      'start_date' => '',
      'end_date' => '',
      'location' => 'Basel',
      'room' => 'R308',
      'canceled' => false,
      'type' => 'lesson',
    ];

    public function test_get_lesson_as_wrong_user()
    {
        $actingUser = User::find(15);

        $response = $this->actingAs($actingUser)->getJson('/api/lessons/1');

        $response->assertStatus(403);
    }

    public function test_get_lesson_as_wrong_teacher()
    {
        $actingUser = User::find(23);

        $response = $this->actingAs($actingUser)->getJson('/api/lessons/1');

        $response->assertStatus(403);
    }

    public function test_update_lesson()
    {
        $actingUser = User::find(1);

        $lessonPost = $this->lessonPost;
        $lessonPost['start_date'] = Carbon::now()->addDay(1)->toDateTimeString();
        $lessonPost['end_date'] = Carbon::now()->addDay(1)->addHour(1)->toDateTimeString();

        $response = $this->actingAs($actingUser)->putJson('/api/lessons/1', $lessonPost);

        $response->assertSuccessful();

        $response->assertJson([ 'location' => 'Basel' ]);
    }

    public function test_delete_lesson()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->deleteJson('/api/lessons/1');

        $response->assertSuccessful();
    }
}
