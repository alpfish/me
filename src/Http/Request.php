<?php

namespace Me\Http;

use Me\Contracts\Http\Request as RequestInterface;

class Request implements RequestInterface
{
    protected static $data = array();

    protected static $self;

    public static function getInstance()
    {
        if (self::$self) return self::$self;
        return self::$self = new self();
    }

    public function __construct()
    {
        //xss 检查
        $this->xssCheck();
        static::$data = array_merge((array)$_GET, (array)$_POST, (array)$_COOKIE, (array)$_FILES);
    }

    /**
     * 获取一个请求数据
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function input($key = null, $default = null)
    {
        return data_get(static::$data, $key, $default);
    }

    /**
     * 获取一组请求数据
     *
     * @param  array|mixed  $keys
     * @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $results = [];

        $input = $this->all();

        foreach ($keys as $key) {
            $results[$key] = data_get($input, $key);
        }

        return $results;
    }

    /**
     * 获取所有请求数据
     *
     * @return array
     */
    public function all()
    {
        return static::$data;
    }

    /**
     * 判断是否存在该请求数据
     *
     * @param  string|array  $key
     * @return bool
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if (is_null(static::$data[$value])) {
                return false;
            }
        }

        return true;
    }

    /**
     * 判断请求头是否存在.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasHeader($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if (is_null($this->getHeader()[$value])) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取请求头.
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function header($key = null, $default = null)
    {
        return data_get($this->getHeader(), $key, $default);
    }

    /**
     * 判断 Cookie 是否存在.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasCookie($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if (is_null($_COOKIE[$value])) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取请求Cookie
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return string|array
     */
    public function cookie($key = null, $default = null)
    {
        return data_get($_COOKIE, $key, $default);
    }

    /**
     * 判断文件是否存在.
     *
     * @param  string|array  $key
     * @return bool
     */
    public function hasFile($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if (is_null($_FILES[$value])) {
                d($value);
                d($_FILES);
                return false;
            }
        }

        return true;
    }

    /**
     * 获取请求文件
     *
     * @param  string  $key
     * @param  string|array|null  $default
     * @return array|null
     */
    public function file($key = null, $default = null)
    {
        return data_get($_FILES, $key, $default);
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

    /**
     * 判断是否为 AJAX 请求
     *
     * @return bool
     */
    public function isAjax()
    {
        return  $this->input('ajax') || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' ==  $_SERVER['HTTP_X_REQUESTED_WITH'])
            ? true : false;
    }

    /**
     * 获取请求方式
     *
     * @return bool
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet()
    {
        return $this->method() == 'GET';
    }

    public function isPost()
    {
        return $this->method() == 'POST';
    }

    public function isPut()
    {
        return $this->method() == 'PUT';
    }

    public function isDelete()
    {
        return $this->method() == 'DELETE';
    }

    public function isHead()
    {
        return $this->method() == 'HEAD';
    }

    public function isOptions()
    {
        return $this->method() == 'OPTIONS';
    }

    public function isPatch()
    {
        return $this->method() == 'PATCH';
    }

    private function xssCheck() {
        static $check = array('"', '>', '<', '\'', '(', ')', 'CONTENT-TRANSFER-ENCODING');
        if($_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $temp = $_SERVER['REQUEST_URI'];
        } elseif(empty ($_GET['formhash'])) {
            $temp = $_SERVER['REQUEST_URI'].file_get_contents('php://input');
        } else {
            $temp = '';
        }
        if(!empty($temp)) {
            $temp = strtoupper(urldecode(urldecode($temp)));
            foreach ($check as $str) {
                if(strpos($temp, $str) !== false) {
                    exit(json_encode(array('status' => 400, 'msg' => '请求参数被污染，服务拒绝响应')) );
                }
            }
        }
        return true;
    }

    private function getHeader()
    {
        static $header = null;
        if (is_null($header)) {
            $server = &$_SERVER;
            foreach ($server as $label => $value) {
                if (substr($label, 0, 5) == 'HTTP_') {
                    // remove the HTTP_* prefix and normalize to lowercase
                    $label = strtolower(substr($label, 5));
                    // convert underscores to dashes
                    $label = str_replace('_', '-', strtolower($label));
                    // retain the header label and value
                    $header[$label] = $value;
                }
            }

            // these two headers are not prefixed with 'HTTP_'
            $rfc3875 = array(
                'CONTENT_TYPE' => 'content-type',
                'CONTENT_LENGTH' => 'content-length',
            );
            foreach ($rfc3875 as $key => $label) {
                if (isset($server[$key])) {
                    $header[$label] = $server[$key];
                }
            }

            // further sanitize headers to remove HTTP_X_JSON headers
            unset($header['HTTP_X_JSON']);
        }

        return $header;
    }

    public function __get($key)
    {
        if (array_key_exists($key, static::$data)) {
            return static::$data[$key];
        }
        return null;
    }
}