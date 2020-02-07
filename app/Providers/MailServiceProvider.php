<?php

namespace App\Providers;

use Swift_Mailer;
use App\Views\View;
use Swift_SmtpTransport;
use App\Mail\Mailer\Mailer;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MailServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Mailer::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(Mailer::class, function () use ($container) {
            $config = $container->get('config');
            $transport = (new Swift_SmtpTransport($config->get('mail.host'), $config->get('mail.port')))
                ->setUsername($config->get('mail.username'))
                ->setPassword($config->get('mail.password'));

            $swift = new Swift_Mailer($transport);

            return (new Mailer($swift, $container->get(View::class)))
                ->alwaysFrom($config->get('mail.from.address'), $config->get('mail.from.name'));
        });
    }
}
