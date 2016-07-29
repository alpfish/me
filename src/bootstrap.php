<?php

/*

$validate = validate(
    request()->only('username', 'mobile','date', 'url'),
    [
        'username' => 'required|min:4|max:12',
        'mobile' => 'required|mobile',
        'date' => 'required|date',
        'url' => 'required|url',
    ],
    [
        'username:required' => '需要填写会员名。',
        'username:min' => '会员名长度需要大于4个字符。',
        'username:max' => '会员名长度需要小于12个字符。',
        'mobile:required' => '需要填写手机号。',
        'mobile:mobile' => '手机号格式不正确。',
    ]
);

if (!$validate->success)
    api('data')->errs($validate->errors)->response();

*/


defined('ME_PATH') || define('ME_PATH', ab_path(__DIR__ . '/../'));

/*
|--------------------------------------------------------------------------
| 加载配置文件
|--------------------------------------------------------------------------
|
| 加载后可直接获取各配置项值
|
*/
//默认配置
me_config()->load(__DIR__ . '/../config/config.php');
//项目配置
me_config()->load(ab_path('/config/me.php'));

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
$capsule->addConnection(me_config('database'));
// 为 Eloquent 模型设置事件调度器，不用可注释掉
// use Illuminate\Events\Dispatcher;
// use Illuminate\Container\Container;
// $capsule->setEventDispatcher(new Dispatcher(new Container));

// 查询构建器 MeDB
$capsule->setAsGlobal();
class_alias('Illuminate\Database\Capsule\Manager', 'MeDB');

// Eloquent ORM
$capsule->bootEloquent();

// 启动包
Me\Api\Router::run();