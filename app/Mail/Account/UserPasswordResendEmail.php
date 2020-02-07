<?php

namespace App\Mail\Account;

use App\Models\ConfirmationToken;
use App\Mail\Mailer\Mailable;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class UserPasswordResendEmail extends Mailable
{
    protected $user;

    protected $reminder;

    public function __construct($user, $reminder)
    {
        $this->user = $user;

        $this->reminder = $reminder;
    }

    public function build()
    {
        return $this->subject("Redefina sua senha")
            ->view('emails/auth/password/recover.twig')
            // ->attach(__DIR__ . '/../../composer.json')
            // ->from('naoresponda@outlook.com', 'Não responda')
            ->with([
                'user' => $this->user,
                'code' => $this->reminder
            ]);
    }
}
