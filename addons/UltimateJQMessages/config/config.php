<?php

return [
    'name' => 'UltimateJQMessages',

    'storage' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'ultimatejqmessages',
        'username' => 'ultimatejqmessages',
        'password' => 'ultimatejqmessages',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
];
