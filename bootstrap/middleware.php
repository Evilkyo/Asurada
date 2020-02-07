<?php

// Whoops
$route->middleware(new App\Middleware\WhoopsMiddleware([
    'enable' => env('APP_DEBUG') === 'true'
]));

foreach ($container->get('config')->get('app.middleware') as $middleware) {
    $route->middleware($container->get($middleware));
}
