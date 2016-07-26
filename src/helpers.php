<?php


/*--------------------------------------------------------------------------
 * 获取包配置项值
 *--------------------------------------------------------------------------
 * 1. 可通过 . 号获取, 最多三层；
 * 2. 用例：
 * me_config()->load(ab_path('/config/sms.php')); //加载配置文件
 * me_config('database'); //返回'database'配置项的值
 * me_config('site.name'); //获取 'site' 下 'name' 配置的值，
 * me_config('foo', 'bar'); //若 'foo' 配置项不存在，则设置 'foo '并返回值 'bar'
 * me_config()->all(); //返回所有配置
 *
 * @param string [name] 配置项名称
 * @param string [default] 默认值，配置选项不存在的话默认值将会被指定并返回
 *
 * @return string | array | Me\Config\Config
 *
 * @author AlpFish 2016/7/25 18:42
 */
if (!function_exists('me_config')) {
    function me_config($name = null, $default = null)
    {
        if (!is_null($name)) return Me\Config\Config::getInstance()->get($name, $default);

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

/*--------------------------------------------------------------------------
 * Api 数据封装与响应
 *--------------------------------------------------------------------------
 * 用例：
 * api(); //返回 Me/Api/Api 实例
 * api('data')->set('foo','bar'); 同 api('data')->set(...);  设置响应数据
 * api('data')->errs('foo', 'bar'); 设置应用错误数据键值
 * api('data')->status(400, '参数不存在:mobile'); 设置调用错误状态码和信息
 * api('data')->response(); 同api()->response(); 响应，默认 JSON 格式
 *
 *
 * @param string $name 功能名称
 *
 * @return mixed
 *
 * @author AlpFish 2016/7/25 19:53
 */
if (!function_exists('api')) {
    function api($name = null)
    {
        if ($name == 'data') {
            return Me\Api\Data::getInstance();
        }
        return Me\Api\Api::getInstance();
    }
}

/*--------------------------------------------------------------------------
 * 将目录或文件路径转化为项目绝对路径
 *--------------------------------------------------------------------------
 *
 * 1. 目录路径末尾自动添加 / ， 文件不会加
 * 2. $path 参数为空默认返回项目根目录路径
 * 3. 项目根目录路径默认服务器设置，可用配置文件 root_path 项重新设置
 *
 * @param string [$path] 路径
 *
 * @return string 格式化后的路径
 *
 * @author AlpFish 2016/7/25 10:50
 */
if (!function_exists('ab_path')) {
    function ab_path($path = null)
    {
        //获取根目录
        $root = empty(me_config('root_path')) ? $_SERVER['DOCUMENT_ROOT'] : me_config('root_path');
        //统一分隔符
        $root = trim(str_replace('\\', '/', $root));
        //尾部加 /, 便于连接子目录
        $root = $root[strlen($root) - 1] === '/' ? $root : $root . '/';

        //返回项目根路径
        if (is_null($path)) return $root;

        //统一分隔符
        $path = trim(str_replace('\\', '/', $path));

        //如果不是文件, 末尾加 /
        $array = explode('/', $path);
        $last = $array[count($array) - 1];
        if (!empty($last)) if (strpos($last, '.') === false) $path = $path[strlen($path) - 1] === '/' ? $path : $path . '/';

        //子目录包含根目录
        if (strpos($path, $root) !== false) {
            return $path;
        }

        return $path[0] === '/' ? $root . substr($path, 1) : $root . $path;
    }
}