<?php

session_start();

use Carbon\Carbon;
use App\Views\View;
use League\Route\Router;
use App\Exceptions\Handler;
use Valitron\Validator as V;
use App\Session\SessionStore;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;

require_once __DIR__ . '/../vendor/autoload.php';

// Timezone
date_default_timezone_set('America/Recife');

// Dotenv
try {
    $dotenv = (Dotenv\Dotenv::create(base_path()))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

// Sentinel
Sentinel::instance(
    new SentinelBootstrapper(
        require(base_path('config/auth.php'))
    )
);

// Carbon
Carbon::setLocale('pt_BR');

// Valitron
V::langDir(base_path('vendor/vlucas/valitron/lang/'));
V::lang('pt-br');

require_once base_path('bootstrap/container.php');

$route = $container->get(Router::class);

require_once base_path('bootstrap/middleware.php');
require_once base_path('routes/web.php');

try {
    $response = $route->dispatch($container->get('request'));
} catch (Exception $e) {
    $handler = new Handler(
        $e,
        $container->get(SessionStore::class),
        $container->get('response'),
        $container->get(View::class)
    );

    $response = $handler->respond();
}
