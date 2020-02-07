<?php

namespace App\Providers;

use App\Views\Extensions\CsrfExtension;
use App\Security\Csrf;
use App\Views\View;
use Twig_Environment;
use Zend\Diactoros\Uri;
use League\Route\Router;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Jralph\Twig\Markdown\Parsedown\ParsedownExtraMarkdown;
use Jralph\Twig\Markdown\Extension;
use App\Views\Extensions\MixExtension;
use App\Views\Extensions\PathExtension;
use App\Views\Extensions\AssetsExtension;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(View::class, function () use ($config, $container) {
            $loader = new Twig_Loader_Filesystem(base_path('resources/views'));

            $twig = new Twig_Environment($loader, [
                'cache' => $config->get('cache.views.path'),
                'debug' => $config->get('app.debug')
            ]);
            
            if ($config->get('app.debug')) {
                $twig->addExtension(new Twig_Extension_Debug);
            }

            $twig->addExtension(new PathExtension(
                $container->get(Router::class),
                $container->get('request')
            ));

            $twig->addExtension(new MixExtension());
            $twig->addExtension(new CsrfExtension($container->get(Csrf::class)));
            $twig->addExtension(new Extension(
                new ParsedownExtraMarkdown
            ));

            // $twig->addGlobal('tags', \App\Models\Tag::get());
            // $twig->addGlobal('categories', \App\Models\Category::get());
            // $twig->addGlobal('lastComments', \App\Models\Comment::orderBy('created_at', 'desc')->get()->take(5));

            return new View($twig);
        });
    }
}
