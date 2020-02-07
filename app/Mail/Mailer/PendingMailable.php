<?php

namespace App\Mail\Mailer;

use App\Mail\Mailer\Mailer;
use App\Mail\Mailer\Mailable;

class PendingMailable
{
    protected $to;
    
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function to($address, $name = null)
    {
        $this->to = compact('address', 'name');

        return $this;
    }

    public function send(Mailable $mailable)
    {
        $mailable->to($this->to['address'], $this->to['name']);

        return $this->mailer->send($mailable);
    }
}
