<?php

namespace App\Providers;

use App\Security\Csrf;
use App\Session\Flash;
use App\Views\View;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class ViewShareServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function boot()
    {
        $container = $this->getContainer();

        $container->get(View::class)->share([
            'config' => $container->get('config'),
            'user' => Sentinel::check(),
            'flash' => $container->get(Flash::class),
            'csrf' => $container->get(Csrf::class),
        ]);
    }

    public function register()
    {
        //
    }
}
