<?php
namespace Alpfish\Me\Api;
/*
|--------------------------------------------------------------------------
| Api 路由器
|--------------------------------------------------------------------------
|
| 1. 路由器根据HTTP请求参数 method 寻址，method表示 Api 方法名
| 2. method 参数中用半角点号 . 代替路径符号 /
| 3. 可以建立 method 的简写形式，简写与全称对应关系由 Api 目录下的 router.php
|    文件以数组形式返回，非简写 method 不用包含在该文件内
| 4. HTTP请求用 version 参数设置 Api 版本号，默认为 v1
| 5. HTTP请求用 format  参数设置 Api 返回数据格式，默认为 JSON
| 6. Api 目录、文件、类名、方法名均用小写命名，类文件默认无后缀以 .php 为扩展名
|
*/

class Router
{
    //默认Api目录
    public static $path = '/app/api/';

    //默认简写路由地图文件名
    public static $routes = 'router.php';

    //默认版本
    public static $version = 'v1';

    //默认控制器类和文件后缀
    public static $suffix = '';

    /**
     * Api 路由器
     *
     * @param string $path      前置路径
     * @param array  $short_map Api名称简写路由地图，如array('cats.get' => 'cats.cats.get')
     * @param string $ext       Api控制器文件扩展名
     *
     * @return api()->response();
     *
     * @author AlpFish 2016/7/24 10:55
     */
    public static function run($path = '')
    {
        //获取正确格式的路径
        $path = empty($path) ? self::$path : $path;
        $path = path_format($path);
        if (!file_exists($path)) return api('data')->status(500, '服务器Api目录设置错误')->response();

        //获取 method
        if (!request('method')) return api('data')->status(400, '缺少请求参数：method')->response();
        $method = self::get_real_method($path);
        //版本处理
        $v = request('version') ? request('version') : self::$version;
        if (!file_exists($path .= $v . '/')) return api('data')->status(404, 'Api版本不正确')->response();

        //路由处理
        $method = explode('.', $method);
        $count = count($method);
        for ($i = 0; $i <= $count - 3; $i++) {
            $path .= $method[$i] . '/';
        }
        $controller = $method[$count - 2] . self::$suffix ;
        $action = $method[$count - 1];
        if (is_file($file = strtolower($path . $controller . self::$suffix. '.php'))) {
            require_once $file;
            if (class_exists($controller) && method_exists($controller, $action)) {
                $class = new $controller;
                return $class->$action();
            }
        }

        return api('data')->status(404, '错误的 api 名称：' . request('method'))->response();
    }

    /**
     * 获取真实Api名
     *
     * @param array $short_map 简写路由地图对照数组
     *
     * @return array
     *
     * @author AlpFish 2016/7/24 11:10
     */
    private static function get_real_method($path)
    {
        $method = mb_strtolower(request('method'));
        //获取Api简写路由列表
        if (is_file($path . 'router.php')) {
            $short_map = (array)require_once $path . self::$routes;
            $short_map = array_change_key_case($short_map);
            $method = array_key_exists($method, $short_map) ? $short_map[$method] : $method;
        }

        return $method;
    }
}
