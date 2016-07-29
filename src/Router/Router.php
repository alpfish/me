<?php
namespace Me\Router;
/*
|--------------------------------------------------------------------------
| Api 路由器
|--------------------------------------------------------------------------
|
| 1. Api 目录、文件名、类名、方法名均小写，类文件名默认无后缀，并以 .php 为扩展名
| 2. 在 url 必须包含带 Api 名称的 method 参数，因为路由根据此参数寻址
| 3. method 值以 . 号分隔，包含路径名.类名.方法名,
| 4. method 可以简写，简写键值以在$routes变量的 routes.php 文件中以返回数组的方式定义
| 5. HTTP请求用 version 参数设置 Api 版本号，默认为 v1
| 6. HTTP请求用 format  参数设置 Api 返回数据格式，默认为 JSON
| 7. 所有静态变量可在路由运行前自定义设置
*/
use Me\Contracts\Api\Router as RouterInterface;

class Router implements RouterInterface
{
    //Api根目录 (可在路由启动前配置此项)
    public static $path = 'app/api/';

    //简写地图文件
    public static $routes = 'routes.php';

    //Api 方法参数
    public static $method = '';

    //默认版本
    public static $version = 'v1';

    //默认返回格式
    public static $format = 'json';

    //默认控制器类和文件后缀
    public static $suffix = '';

    /**
     * Api 路由器
     *
     * @return api()->response();
     *
     * @author AlpFish 2016/7/24 10:55
     */
    public static function run()
    {
        //获取路径
        $path = ab_path(self::$path);
        if (!file_exists($path)) return api('data')->status(500, '服务器Api目录设置错误')->response();

        //获取method
        if (!request('method') && empty(self::$method)) return api('data')->status(400, '缺少请求参数：method')->response();
        $method = self::get_real_method($path);
        //版本处理
        $v = request('version') ? request('version') : self::$version;
        if (!file_exists($path .= $v . '/')) return api('data')->status(404, 'Api版本不正确')->response();
        //路由寻址
        $method = explode('.', $method);
        $count = count($method);
        for ($i = 0; $i <= $count - 3; $i++) {
            $path .= $method[$i] . '/';
        }
        $controller = $method[$count - 2] . self::$suffix ;
        $action = $method[$count - 1];
        if (is_file($file = $path . strtolower($controller . self::$suffix. '.php'))) {
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
     * @param array $path
     *
     * @return array
     *
     * @author AlpFish 2016/7/24 11:10
     */
    protected static function get_real_method($path)
    {
        $method = empty(self::$method) ? mb_strtolower(request('method')) : self::$method;
        //获取Api简写路由列表
        if (is_file($path . self::$routes)) {
            $short_map =  array_change_key_case((array) require $path . self::$routes);
            $method = array_key_exists($method, $short_map) ? $short_map[$method] : $method;
        }

        return $method;
    }
}
