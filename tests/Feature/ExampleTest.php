<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Call this template method before each test method is run.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed', [ '--class' => 'SmallSchool' ]);

        $this->withoutMiddleware([
          \Illuminate\Auth\Middleware\Authenticate::class,
        ]);
    }

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
