<?php


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
        //获取根目录 & 统一分隔符
        $root = str_replace('\\', '/', realpath(__DIR__ . '/../../../../')) . '/';

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

/*--------------------------------------------------------------------------
 * 获取包配置项值
 *--------------------------------------------------------------------------
 * 1. 可通过 . 号获取；
 * 2. 用例：
 * me_config()->load(ab_path('/config/sms.php')); //加载配置文件
 * me_config('database'); //返回'database'配置项的值
 * me_config('site.name'); //获取 'site' 下 'name' 配置的值，
 * me_config('foo', 'bar'); //若 'foo' 配置项不存在，则设置 'foo '并返回值 'bar'
 * me_config()->all(); //返回所有配置
 *
 * @param string [name] 配置项名称
 * @param string [default] 默认值，配置选项不存在的话默认值将会被设置并返回
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

/**
 * 获取HTTP客户端请求数据
 *
 * @param  string  [$key]  要获取的参数键
 * @param  string  [$default]  键不存在时返回的值
 * @return mixed
 *
 * @e.g
 * request('id');  ===  request()->id;  //返回同一个数据的两种用法
 * request('name.firstname');  //使用点语法
 * request()->only(['id', 'name']);   //返回多个数据
 * request()->has('pic');  //数据判断
 * request()->all();  //所有数据
 * request()->file('img');  //文件数据
 * request()->isPost();  //请求方式判断
 * ...
 *
 * @auth: AlpFish 2016/7/25 9:26
 */
if (!function_exists('request')) {
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return Me\Http\Request::getInstance();
        }
        return Me\Http\Request::getInstance()->input($key, $default);
    }
}

/**
 * 转换 HTML entities 特殊字符
 *
 * @param  string  $value
 * @return string
 */
if (! function_exists('e')) {
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }
}

/*------------------------------------------------------------------------------------------------
 *
 *                                 来自于 Laravel 5.2
 *
 *-------------------------------------------------------------------------------------------------
 *
 * 此类帮助函数在个人包中所很多使用的地方，切不可随意修改删除
 *
 * 若引入更多可见 Illuminate\Support 命令空间下的 helpers.php
 *
 * 具体用法可见 Laravel 5.2 手册中的帮助函数和集合。
 *
 */
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

/**
 * 用给定参数值创建一个集合
 *
 * 集合有强大的数组操作功能，详细见 Laravel 5.2 中文手册。
 *
 * @param  mixed  $value
 * @return \Illuminate\Support\Collection
 */
if (! function_exists('collect')) {
    function collect($value = null)
    {
        return new Collection($value);
    }
}

/**
 * 从数组/对象中获取数据, 支持点语法
 * Get an item from an array or object using "dot" notation.
 *
 * @param  mixed   $target
 * @param  string|array  $key
 * @param  mixed   $default
 * @return mixed
 */
if (! function_exists('data_get')) {
    function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (($segment = array_shift($key)) !== null) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return value($default);
                }

                $result = Arr::pluck($target, $key);

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }
}

/**
 * （以覆盖的方式）为数组或对象填充数据, 支持点语法
 * Set an item on an array or object using dot notation.
 *
 * @param  mixed  $target
 * @param  string  $key
 * @param  mixed  $value
 * @param  bool  $overwrite
 * @return mixed
 */
if (! function_exists('data_set')) {
    function data_set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if (! Arr::accessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    data_set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (Arr::accessible($target)) {
            if ($segments) {
                if (! Arr::exists($target, $segment)) {
                    $target[$segment] = [];
                }

                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || ! Arr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (! isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                data_set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ($segments) {
                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }
}