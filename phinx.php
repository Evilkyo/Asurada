<?php

require_once base_path('/bootstrap/app.php');

return [
    'paths' => [
        'migrations' => 'database/migrations'
    ],
    'migration_base_class' => 'App\Database\Migrations\Migration',
    'templates' => [
        'file' => 'app/Database/Migrations/MigrationStub.php'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default' => [
            'adapter' => env('DB_TYPE'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'name' => env('DB_DATABASE'),
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
        ]
    ]
];
