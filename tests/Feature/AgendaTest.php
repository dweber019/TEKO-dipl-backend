<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    public function test_list_agenda()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->getJson('/api/agenda');

        $response->assertStatus(200);

        $response->assertJson([
          [ 'name' => 'Englisch' ]
        ]);
    }
}
