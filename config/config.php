<?php

return [
    // Mysql 配置
    "database" =>  [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'haidao',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => 'hd_',
    ],

    // redis
    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port'     => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ]
];
