<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Helpers\UserInvitation as UserInvitationModel;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invitation instance.
     *
     * @var UserInvitationModel
     */
    public $invitation;


    /**
     * Create a new message instance.
     *
     * @param UserInvitationModel $invitation
     */
    public function __construct(UserInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
          ->from('no-reply@dipl.w3tec.ch')
          ->view('emails.user.invitation');
    }
}
