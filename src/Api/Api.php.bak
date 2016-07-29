<?php
namespace Me\Api;
/**
 * Auth: AlpFish.
 * Date: 2016/7/21 14:12
 */

class Api
{
    protected static $self;

    //单例模式
    public static function getInstance()
    {
        if (self::$self) return self::$self;
        return self::$self = new self();
    }

    //Api 响应数据封装
    public static function data()
    {
        return Data::getInstance();
    }

    //应用内部获取 Api 数据 (可能会导致死循环)
    /*
    public static function get($method, $param = array(), $version = 'v1')
    {
        //返回原生数据
        $_REQUEST['format'] = 'saw';
        Router::$method = $method;
        Router::$version = $version;
        foreach ($param as $k => $v) {
            $_REQUEST[$k] = $v;
        }
        return Router::run();
    }
    */

    //Api 响应
    public static function response()
    {
        return Data::getInstance()->response();
    }
}