<?php

namespace Me\Http;

use Me\Contracts\Http\Request as RequestInterface;

class Request implements RequestInterface
{
    public function method()
    {

    }

    public function get($key)
    {

    }

    public function has($key)
    {

    }

    public function all()
    {

    }

    /**
     * 获得客户端真实IP
     *
     * @return string|null
     */
    public function ip()
    {
        global $ip;
        $unknown = null;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        //处理多层代理的情况,或者使用:
        //if (false !== strpos($ip, ',')) $ip = reset(explode(',', $ip));
        $ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;

        return $ip;
    }
}