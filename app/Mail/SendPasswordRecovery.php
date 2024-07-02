<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordRecovery extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $token;
    public $user;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('emails.passwordRecovery')
            ->with(
                [
                    'token' => $this->token,
                    'user' => $this->user
                ]
            );
    }
}
