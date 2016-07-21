<?php
namespace Alpfish\Me\Api;
/**
 * Auth: AlpFish.
 * Date: 2016/7/21 13:44
 */

class Router
{

    public static function run($api_path = '', $api_ext = '.php')
    {
        //Api 根目录
        if( !file_exists($api_path)) exit('Api 根目录不存在！');
        //Api 版本
        $v = request('v') ? request('v') : 'V1';
        if( !file_exists($api_path .= $v . '/')) exit('$v： 版本不正确');
        //Api 名称
        if( !$method = request('method')) exit('$method： 参数不存在');

        //Api 子目录路径， 参数 method 中以 . 分隔
        $method = explode('.', $method);
        $count = count($method);
        for($i = 0; $i <= $count - 3; $i++) {
            $api_path .= $method[$i] . '/';
        }
        
        $controller = $method[$count - 2];
        $action = $method[$count - 1];
        $file = $api_path . $controller . $api_ext;

        if (is_file($file) && require_cache($file) && class_exists($controller)  && method_exists($controller, $action)) {
            $class = new $controller;
            return $class->$action();
        }

        exit('$method： Api 不存在');
    }
}
