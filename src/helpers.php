<?php

if (!function_exists('request')) {
    //请求数据
    function request($name = null)
    {
        if (is_string($name)) {
            //todo 包在海盗系统前引入，需对请求数据处理
            return $_REQUEST[$name];
        }
        return null;
    }
}

if (!function_exists('api')) {
    //api 封装
    function api($name = null)
    {
        if ($name == 'data') {
            return Alpfish\Me\Api\Data::getInstance();
        }
        return Alpfish\Me\Api\Api::getInstance();
    }
}

if (!function_exists('path_format')) {
    /*
    |--------------------------------------------------------------------------
    | 目录路径正确格式化
    |--------------------------------------------------------------------------
    |
    | 1. 相对或绝对路径都转化为服务器绝对路径
    | 2. 首尾正确处理分隔符 /
    |
    */
    function path_format($path)
    {
        $path = trim(str_replace('\\', '/', $path));
        if (strpos($path, $_SERVER['DOCUMENT_ROOT']) === false && strpos($path, $_SERVER['DOCUMENT_ROOT']) !== 0) {
            $path = $path[0] === '/' ? $_SERVER['DOCUMENT_ROOT'] . $path : $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
        }
        $path = $path[strlen($path) - 1] === '/' ? $path : $path . '/';

        return $path;
    }
}