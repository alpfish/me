<?php


// 加载配置
$config_me = require __DIR__ . '/../config/config.php';

/*
|--------------------------------------------------------------------------
| 项目使用 Eloquent ORM
|--------------------------------------------------------------------------
|
| 1. 选择 Laravel 的 Eloquent 作为项目的 ORM，不仅能使代码更加优雅，而且有完善的文档。
| 2. 引入下面两个依赖包(alpfish/me 的 composer.json 中已引入)：
|    illuminate/database, illuminate/events
| 3. 项目模型继承 Illuminate\Database\Eloquent\Model 即可。具体使用见官方文档。
|
*/
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection($config_me['database']);

// 为 Eloquent 模型设置事件调度器，不用可注释掉
// use Illuminate\Events\Dispatcher;
// use Illuminate\Container\Container;
// $capsule->setEventDispatcher(new Dispatcher(new Container));

// 查询构建器 MyDB
$capsule->setAsGlobal();
class_alias('Illuminate\Database\Capsule\Manager','MyDB');

// Eloquent ORM
$capsule->bootEloquent();

