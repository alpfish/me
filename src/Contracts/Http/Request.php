<?php
namespace Me\Contracts\Http;

interface Request
{

    public function input($key = null, $default = null);

    /**
     * 获取一组请求数据
     *
     * @param  array|mixed  $keys
     * @return array
     */
    public function only($keys);

    /**
     * 获取所有请求数据
     *
     * @return array
     */
    public function all();

    /**
     * 判断是否存在该请求数据
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($key);

    /**
     * 判断请求头是否存在.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasHeader($key);

    /**
     * 获取请求头.
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function header($key = null, $default = null);

    /**
     * 判断 Cookie 是否存在.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasCookie($key);

    /**
     * 获取请求Cookie
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function cookie($key = null, $default = null);

    /**
     * 判断文件是否存在.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function hasFile($key);

    /**
     * 获取请求文件
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return array|null
     */
    public function file($key = null, $default = null);

    /**
     * 获得客户端真实IP
     *
     * @return string|null
     */
    public function ip();

    /**
     * 判断是否为 AJAX 请求
     *
     * @return bool
     */
    public function isAjax();

    /**
     * 是否为 手机/平板客户端
     *
     * @return bool
     */
    public function isMobile();

    /**
     * 是否为 平板客户端
     *
     * @return bool
     */
    public function isTablet();

    /**
     * 是否为 微信客户端
     *
     * @return bool
     */
    public function isWeChat();

    /**
     * 获取请求方式
     *
     * @return bool
     */
    public function method();

    /**
     * 是否为某种请求方式
     *
     * @param string $method
     * @return bool
     */
    public function isMethod($method);

}