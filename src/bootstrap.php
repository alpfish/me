<?php

$api = new Me\Api\Application;

use Illuminate\Database\Capsule\Manager as DB;
$data = DB::table('goods_sku')->get();

//$data = Database::table('member')->get();

ddd($data);


// 启动路由
$api::run();