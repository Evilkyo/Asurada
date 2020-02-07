<?php

namespace App\Mail\Mailer;

use Swift_Mailer;
use Swift_Message;
use App\Views\View;
use App\Mail\Mailer\Mailable;
use App\Mail\Mailer\MessageBuilder;
use App\Mail\Mailer\Contracts\MailableContract;

class Mailer
{
    protected $swift;
    
    protected $view;

    protected $from;

    public function __construct(Swift_Mailer $swift, View $view)
    {
        $this->swift = $swift;
        $this->view = $view;
    }

    public function to($address, $name = null)
    {
        return (new PendingMailable($this))->to($address, $name);
    }

    public function alwaysFrom($address, $name = null)
    {
        $this->from = compact('address', 'name');

        return $this;
    }

    public function send($view, $viewData = [], Callable $callback = null)
    {
        if ($view instanceof MailableContract) {
            return $this->sendMailable($view);
        }

        $message = $this->buildMessage();

        call_user_func($callback, $message);

        $message->body($this->parseView($view, $viewData));

        return $this->swift->send($message->getSwiftMessage());
    }

    protected function sendMailable(Mailable $mailable)
    {
        return $mailable->send($this);
    }

    protected function buildMessage()
    {
        return (new MessageBuilder(new Swift_Message))
            ->from($this->from['address'], $this->from['name']);
    }

    protected function parseView($view, $viewData = [])
    {
        return $this->view->fetch($view, $viewData);
    }
}
