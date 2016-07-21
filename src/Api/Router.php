<?php
namespace Alpfish\Me\Api;
/**
 * Auth: AlpFish.
 * Date: 2016/7/21 13:44
 */
class Router
{
    public static function run($api_path = '')
    {
        if( !file_exists($api_path))    exit('Api 根目录不存在！');

        //Api 版本
        $v = request('v') ? request('v') : 'V1';
        if( !file_exists($api_path.$v))     exit('Api 版本不正确');

        //Api 方法
        //$method = $_REQUEST['method'] ? $_REQUEST['method'] : '';
        if (request('method'));

        //Api 目录
        //$sub_path = explode('.', $method);
    }
}