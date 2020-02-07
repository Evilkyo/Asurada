<?php

namespace App\Mail\Auth\Activation;

use App\Models\ConfirmationToken;
use App\Mail\Mailer\Mailable;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class ActivationEmail extends Mailable
{
    protected $credentials;
    
    protected $activation;

    public function __construct($credentials, $activation)
    {
        $this->credentials = $credentials;
        $this->activation = $activation;
    }

    public function build()
    {
        return $this->subject("Ative sua conta")
            ->view('emails/auth/activation.twig')
            // ->attach(__DIR__ . '/../../composer.json')
            // ->from('naoresponda@outlook.com', 'Não responda')
            ->with([
                'user' => $this->credentials,
                'activation' => $this->activation
            ]);
    }
}
