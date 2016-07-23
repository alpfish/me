<?php
use Illuminate\Database\Capsule\Manager as Capsule;

// 加载配置
$config = require '../config/config.php';

/**
 * 
 * Eloquent ORM
 * 
 * ============
 *   使用方法:
 * ============
 * 
 * class User extends Illuminate\Database\Eloquent\Model {}
 * 
 * $users = User::where('votes', '>', 1)->get();
 *
 */
$capsule = new Capsule;
$capsule->addConnection($config['database']);
$capsule->bootEloquent();