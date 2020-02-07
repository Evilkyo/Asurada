<?php

namespace App\Providers;

use League\Fractal\Manager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class FractalServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Manager::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(Manager::class);
    }
}
