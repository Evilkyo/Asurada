<?php

namespace App\Providers;

use Intervention\Image\ImageManager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ImageServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'image'
    ];

    public function register()
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share('image', function () use ($container, $config) {
            $manager = new ImageManager($config->get('image.driver'));

            return $manager;
        });
    }
}
