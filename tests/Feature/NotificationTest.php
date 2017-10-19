<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_list_notification()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/users/2/notifications');

        $response->assertSuccessful();
    }

    public function test_mark_notification_as_read()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/users/2/notifications/1/read', []);

        $response->assertSuccessful();
    }
}
