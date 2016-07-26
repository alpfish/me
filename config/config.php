<?php
return [
    //项目根目录的绝对路径, 其他路径均相对于此路径
    //"root_path" => 'D:\xampp\htdocs\haidao-learn/',
    // Api 目录配置
    "api_path" => '/app/api/',
    
    // Mysql 配置
    "database" =>  [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'haidao',
        'username'  => 'root',
        'password'  => 'dd',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => 'hd_',
    ],

    // redis
    'redis' => [

    ],
    "a" => array(
        "b" => array(
            "c" => 'a.b.c'
        )
    )
];
