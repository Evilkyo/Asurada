<?php

namespace App\Mail\Account;

use App\Mail\Mailer\Mailable;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class PasswordUpdated extends Mailable
{
    public function build()
    {
        return $this->subject("Senha atualizada")
            ->view('emails/account/password/updated.twig')
            // ->attach(__DIR__ . '/../../composer.json')
            // ->from('naoresponda@outlook.com', 'Não responda')
            ->with([
                // 'user' => Sentinel::check()
            ]);
    }
}
