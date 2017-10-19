<?php

namespace Tests\Feature;

use App\Mail\UserInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserInvitationTest extends TestCase
{
    private $userPost = [
      'firstname' => 'Hans',
      'lastname' => 'Müller',
      'type' => 'student',
      'inviteEmail' => 'b@b.com',
    ];

    private $userPostIncorrect = [
      'firstname' => 'Hans',
      'lastname' => 'Müller',
      'type' => 'student',
      'inviteEmail' => 'invalid-email',
    ];

    public function test_admin_can_create_user()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->postJson('/api/users', $this->userPost);

        $response->assertSuccessful();
    }

    public function test_wrong_input_data()
    {
        $actingUser = User::find(1);

        $response = $this->actingAs($actingUser)->postJson('/api/users', $this->userPostIncorrect);

        $response->assertStatus(422);
    }

    public function test_student_is_not_allwoed_to_create_user()
    {
        $actingUser = User::find(2);

        $response = $this->actingAs($actingUser)->postJson('/api/users', $this->userPost);

        $response->assertStatus(403);
    }

    public function test_mail_on_create_user()
    {
        $actingUser = User::find(1);

        Mail::fake();

        $response = $this->actingAs($actingUser)->postJson('/api/users', $this->userPost);
        $response->assertSuccessful();

        Mail::assertSent(UserInvitation::class, 1);

        $userInvite = $this->userPost;

        Mail::assertSent(UserInvitation::class, function ($mail) use ($userInvite) {
            return $mail->hasTo($userInvite['inviteEmail']);
        });

        Mail::assertSent(UserInvitation::class, function ($mail) use ($userInvite) {
            return $mail->invitation->firstname === $userInvite['firstname'] &&
              $mail->invitation->lastname === $userInvite['lastname'];
        });
    }

}
