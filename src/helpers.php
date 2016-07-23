<?php

if ( ! function_exists('request'))
{
    //请求数据
    function request($name = null)
    {
        if(is_string($name)) {
            //todo 包在海盗系统前引入，需对请求数据处理
            return $_REQUEST[$name];
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