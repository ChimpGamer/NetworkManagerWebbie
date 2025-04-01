<?php

return [
    'name' => 'UltimateTags',

    'storage' => [
        'driver' => 'mysql',
        'host' => 'st02.cremers.local',
        'port' => 3307,
        'database' => 'UltimateTags',
        'username' => 'NetworkManager',
        'password' => 'NetworkManager',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
];
