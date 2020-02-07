<?php

namespace App\Providers;

use App\Config\Config;
use App\Config\Loaders\ArrayLoader;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'config'
    ];

    public function register()
    {
        $this->getContainer()->share('config', function () {
            $loader = new ArrayLoader([
                'app' => base_path('config/app.php'),
                'auth' => base_path('config/auth.php'),
                'cache' => base_path('config/cache.php'),
                'db' => base_path('config/db.php'),
                'mail' => base_path('config/mail.php'),
                'image' => base_path('config/image.php'),
                'misc' => base_path('config/misc.php'),
            ]);

            return (new Config)->load([$loader]);
        });
    }
}
