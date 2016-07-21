<?php

if ( ! function_exists('request'))
{
    //请求数据
    function request($name = null)
    {
        if(is_string($name)) {
            //暂时返回经由海盗系统处理过的 $_GET 数据（已包括$_POST）
            return $_GET[$name];
        }
        return null;
    }
}

if ( ! function_exists('api'))
{
    //api 封装
    function api($name = null)
    {
        if($name == 'data') {
            return Alpfish\Me\Api\Data::getInstance();
        }
        return Alpfish\Me\Api\Api::getInstance();
    }
}