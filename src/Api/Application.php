<?php
namespace Me\Api;

use Me\Router\Router;
use Illuminate\Container\Container;

class Application extends Container
{
    //启动容器
    public function __construct($basePath = null)
    {
        static::setInstance($this);
        $this->register();
    }

    //容器注册
    protected function register()
    {
        $this->instance('app', $this);
        $this->instance('config', \Me\Config\Config::getInstance());
        $this->instance('data', Data::getInstance());
        $this->instance('db', \Me\Database\Database::getInstance());
    }

    //Api 响应数据封装
    public static function data()
    {
        return Data::getInstance();
    }

    //数据响应
    public static function response()
    {
        return Data::getInstance()->response();
    }

    //启动路由
    public static function run()
    {
        return Router::run();
    }
}