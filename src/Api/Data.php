<?php
namespace Me\Api;

use Me\Contracts\Api\Data as DataContract;

/*----------------------
 *    Api 数据封装与响应
 *----------------------
 *
 * 1. 使用帮助函数 api('data') 或 api()->data()
 * 2. 支持方法链, response()放最后
 * 3. Api 响应数据：
 *    {
 *      "status": 200,    //响应状态 默认200：响应成功，4XX: 客户端非法请求(参数错误等), 5XX: 服务器运行错误
 *      "msg": "Success", //响应信息 默认"Success", api('data')->status(400, '缺少参数:name');
 *      "data":{
 *          "foo": "bar" //应用数据 api('data')->set('foo', 'bar');
 *          "errs": [ ], //应用错误数据 api('data')->errs('foo', 'bar');
 *      },
 *    }
 *
 * 4. 响应数据固定参数作用：
 *    status 和 msg     返回响应状态，为前端提供 Api 调用错误的信息提示，便于开发。
 *    data              处理成功的响应数据。
 *    data.errs         返回应用逻辑处理的错误数据， 如对用户提交的表单字段验证失败，登录用户 Token 失效需重新登录等。
 *
 * 4. 默认响应格式为JSON，请求参数 $format 可指定 XML
 *
 * */

class Data implements DataContract
{
    //响应状态码, 默认200：响应成功，4XX: 客户端非法请求, 5XX: 服务器运行错误
    protected $status = 200;

    //状态码
    protected $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );

    //响应状态信息
    protected $msg = 'Success';

    //应用数据
    protected $data = array();

    //应用错误数据
    protected $errs = array();

    //单例模式
    protected static $self;

    /* *
     * @return self
     * 在帮助函数中实现：return \Alp\Api\Data\Data::getInstance(); 便于 IDE 使用 data() 函数提示
     * */
    public static function getInstance()
    {
        if (self::$self) return self::$self;
        return self::$self = new self();
    }

    /* *
    * 设置应用数据(支持数组)
    *
    * @param  string|array $key
    * @param  mixed  $value
    * @return $this
    * */
    public function set($key, $value = null)
    {
        if (is_array($key)) {        // 数组
            $this->data = array_merge($this->data, $key);
            return $this;
        }
        if (is_string($key)) {        // 键值对
            $this->data = array_merge($this->data, array($key => $value));
            return $this;
        }
        $this->data = array_merge($this->data, array($key));        // 其他

        return $this;
    }

    /* *
     * 设置应用错误数据
     *
     * @param  string|array $key
     * @param  string  $value
     * @return $this
     * */
    public function errs($key, $value = null)
    {
        if (is_array($key)) {        // 数组
            $this->errs = array_merge($this->errs, $key);
            return $this;
        }
        if (is_string($key) && !is_null($value)) {        // 键值对且有错误值
            $this->errs = array_merge($this->errs, array($key => $value));
            return $this;
        }
        $this->errs = array_merge($this->errs, array($key));        // 其他

        return $this;
    }

    /* *
     * 设置响应状态信息
     *
     * @param  int $code
     * @param  string  $msg
     * @return $this
     * */
    public function status($code, $msg)
    {
        $this->status = (int)$code;
        $this->msg = (string)$msg;
        //发送响应头,前端应注意处理
        if (array_key_exists($code, $this->_status)) {
            header('HTTP/1.1 ' . $code . ' ' . $this->_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:' . $code . ' ' . $this->_status[$code]);
        }

        return $this;
    }

    /* *
     * Api 响应
     *
     * @return Response
     * */
    public function response()
    {
        //允许跨域请求
        header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Origin: http://localhost:8080");

        // XML响应
        $format = empty(request('format')) ? 'JSON' : request('format');

        if(mb_strtoupper($format) === 'SAW') return $this->get_responseData();

        if (strtoupper($format) === 'XML') {
            $content = $this->xmlEncode($this->get_responseData());
            header('Content-Type: text/xml; charset=utf-8');
            echo $content; exit();
        }

        // JSON响应
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->get_responseData());
    }

    /* *
     * 获取数据
     *
     * @param  string $key
     * @return mixed
     * */
    public function get($key = null)
    {
        if (is_string($key)) return isset($this->data[$key]) ? $this->data[$key] : null;
        return $this->get_responseData();
    }

    // 封装响应数据
    private function get_responseData()
    {
        $this->data['errs'] = $this->errs;
        $response = array('status' => $this->status, 'msg' => $this->msg, 'data' => $this->data);
        return $response;
    }

    /* *
     * xml编码
     *
     * @param array $data 数据
     * return string
     * */
    private function xmlEncode($data = array())
    {
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .= $this->xmlToEncode($data);
        $xml .= "</root>";
        return $xml;
    }

    private function xmlToEncode($data)
    {
        $xml = "";
        foreach ($data as $key => $value) {
            $attr = "";
            if (is_numeric($key)) {
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value;
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }
}