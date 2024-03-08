<?php

return [
    'name' => 'UltimateTags',

    'connections' => [
        'ultimatetags' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => 'st02.cremers.local',
            'port' => 3307,
            'database' => 'UltimateTags',
            'username' => 'NetworkManager',
            'password' => 'NetworkManager',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => 'InnoDB',
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ],

    'ultimatetags' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => 'st02.cremers.local',
        'port' => 3307,
        'database' => 'UltimateTags',
        'username' => 'NetworkManager',
        'password' => 'NetworkManager',
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => false,
        'engine' => 'InnoDB',
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
];
