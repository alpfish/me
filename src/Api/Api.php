<?php
namespace Alpfish\Me\Api;
/**
 * Auth: AlpFish.
 * Date: 2016/7/21 14:12
 */

class Api
{
    protected static $self;

    //单例模式
    public static function getInstance() {
        if (self::$self)
            return self::$self;
        return self::$self = new self();
    }

    //Api 响应数据封装
    public static function data()
    {
        return Data::getInstance();
    }

    //Api 响应
    public static function response()
    {
        self::data()->response();
    }
}