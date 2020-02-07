<?php

return [
    'name' => env('APP_NAME'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL'),
    'email' => env('APP_EMAIL'),

    'providers' => [
        'App\Providers\AppServiceProvider',
        'App\Providers\ViewServiceProvider',
        'App\Providers\DatabaseServiceProvider',
        'App\Providers\SessionServiceProvider',
        'App\Providers\FlashServiceProvider',
        'App\Providers\CsrfServiceProvider',
        'App\Providers\ValidationServiceProvider',
        'App\Providers\ViewShareServiceProvider',
        'App\Providers\PaginationServiceProvider',
        'App\Providers\ImageServiceProvider',
        'App\Providers\FractalServiceProvider',
        'App\Providers\MailServiceProvider',
    ],

    'middleware' => [
        'App\Middleware\ShareValidationErrors',
        'App\Middleware\ClearValidationErrors',
        'App\Middleware\CsrfGuard',
    ]
];
