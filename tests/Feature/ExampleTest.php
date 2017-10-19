<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->deleteJson('/api/users/1');

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
          'invite_email' => 'david.weber.schenker@gmail.com'
        ]);
    }
}
