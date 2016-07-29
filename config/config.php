<?php
return [
    // Api 目录配置
    "api_path" => '/app/api/',
    
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
    ],
];
