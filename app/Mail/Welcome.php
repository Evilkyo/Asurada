<?php

namespace App\Mail;

use App\Mail\Mailer\Mailable;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class Welcome extends Mailable
{

    public function build()
    {
        return $this->subject("Bem-vindo ao MeuApp")
            ->view('emails/welcome.twig')
            // ->attach(__DIR__ . '/../../composer.json')
            // ->from('naoresponda@outlook.com', 'Não responda')
            ->with([
                'user' => Sentinel::check()
            ]);
    }
}
