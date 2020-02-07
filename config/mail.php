<?php

return [
    'host' => env('MAIL_HOST'),
    'port' => env('MAIL_PORT'),
    'from' => [
        'name' => env('MAIL_FROM_NAME'),
        'address' => env('MAIL_FROM_ADDRESS')
    ],
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
];
