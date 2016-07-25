<?php

/*
|--------------------------------------------------------------------------
| 获取包配置项值
|--------------------------------------------------------------------------
|
| 使用 me_ 前缀避免冲突，用 . 号表示配置层级, 最多三层；默认返回全部。
|
| @auth: AlpFish 2016/7/24 17:04
*/
if (!function_exists('me_config')) {
    function me_config($name = null, $default = null)
    {
        if(!is_null($name)) return Me\Config\Config::getInstance()->get($name, $default);

        return Me\Config\Config::getInstance();
    }
}

/*
|--------------------------------------------------------------------------
| 获取HTTP请求数据
|--------------------------------------------------------------------------
|
| 包括 GET 和 POST 数据。
|
| @auth: AlpFish 2016/7/25 9:26
*/
if (!function_exists('request')) {
    function request($name = null, $default = null)
    {
        static $request = array();
        $request = array_merge($request, (array)$_GET, (array)$_POST);
        if (is_string($name) && isset($request[$name])) {
            return $request[$name];
        }
        return null;
    }
}

//api 封装
if (!function_exists('api')) {
    function api($name = null)
    {
        if ($name == 'data') {
            return Me\Api\Data::getInstance();
        }
        return Me\Api\Api::getInstance();
    }
}

/*
|--------------------------------------------------------------------------
| 将目录或文件路径转化为项目绝对路径
|--------------------------------------------------------------------------
|
| 1. 并自动处理添加分隔符 /
| 2. 若域名入口不为项目根目录，则需配置 me.config 文件的 root_path
|
*/
if (!function_exists('ab_path')) {
    function ab_path($path = null)
    {
        //获取根目录
        $root = empty(me_config('root_path')) ? $_SERVER['DOCUMENT_ROOT'] : me_config('root_path');
        //末尾去掉 / 符号，用于比较子目录是否包含根目录
        $root = trim(str_replace('\\', '/', $root));
        $root = $root[strlen($root) - 1] === '/' ? substr($root,0,strlen($root)-1) : $root;

        if (is_null($path)) return $root . '/';

        //子目录 / 符号转换
        $path = trim(str_replace('\\', '/', $path));

        //如果不是文件, 末尾加 /
        $array = explode('/', $path);
        $last = $array[count($array)-1];
        if (!empty($last)) {
            if (!strpos($last, '.')) {
                $path = $path[strlen($path) - 1] === '/' ? $path : $path . '/';
            }
        }

        //子目录包含根目录
        if (strpos($path, $root) !== false) {
            return $path;
        }

        return $path[0] === '/' ? $root . $path : $root . '/' . $path;
    }
}