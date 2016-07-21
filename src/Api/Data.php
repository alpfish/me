<?php
namespace Alpfish\Me\Api;

use Alpfish\Me\Contracts\Api\Data as DataContract;

/*----------------------
 *    Api 数据封装与响应
 *----------------------
 *
 * 1. 使用帮助函数 api('data') 或 api()->data() 调用
 * 2. 支持方法链, response()放最后
 * 3. 错误数据响应类型：
 *    (1)调用错误, 用于判断Api调用是否成功
 *    (2)应用错误，用于处理应用逻辑中的错误提示信息返回
 * 4. Api 数据设置 与 响应格式：
 *    {
 *      "err": 0, //调用错误 api()->err('foo', 1);
 *      "msg": 'Success', //调用错误信息
 *      "data":[
 *          "errs": [ ], //应用错误 api('data')->err('username', '用户名已存在');
 *          "foo": "bar" //应用数据 api('data')->set('foo', 'bar');
 *      ],
 *      "help": ''
 *    }
 * 5. 默认响应格式为JSON，请求参数 $format 可指定 XML
 *
 * */

class Data implements DataContract
{
    //调用错误代码, 为 0 时调用成功
    protected $api_err = 0;

    //调用错误信息
    protected $api_msg = 'Success';

    //应用数据
    protected $data = array();

    //应用错误数据
    protected $data_errs = array();

    //单例模式
    protected static $self;

    /* *
     * @return self
     * 在帮助函数中实现：return \Alp\Api\Data\Data::getInstance(); 便于 IDE 使用 data() 函数提示
     * */
    public static function getInstance() {
        if (self::$self)
            return self::$self;
        return self::$self = new self();
    }

    /* *
    * 设置应用数据(支持数组)
    *
    * @param  string|array $key
    * @param  mixed  $value
    * @return $this
    * */
    public function set($key, $value = null) {
        if (is_array($key)) {        // 数组
            $this->data = array_merge($this->data, $key);
            return $this;
        }
        if (! is_object($key)) {        // 键值对
            $this->data = array_merge($this->data, array($key => $value));
            return $this;
        }
        $this->data = array_merge($this->data, array($key));        // 对象

        return $this;
    }

    /* *
     * 设置应用错误数据
     *
     * @param  string|array $key
     * @param  mixed  $value
     * @return $this
     * */
    public function err($key, $value = null) {
        if (is_array($key)) {        // 数组
            $this->data_errs = array_merge($this->data_errs, $key);
            return $this;
        }
        if (! is_object($key)) {        // 键值对
            $this->data_errs = array_merge($this->data_errs, array($key => $value));
            return $this;
        }
        $this->data_errs = array_merge($this->data_errs, array($key));        // 对象

        return $this;
    }

    /* *
     * 设置调用错误数据
     *
     * @param  int $code
     * @param  string  $msg
     * @return $this
     * */
    public function api_err($msg, $code = 400) {
        $this->api_err = $code;
        $this->api_msg = $msg;
        return $this;
    }

    /* *
     * Api 响应
     *
     * @return Response
     * */
    public function response() {
        // XML响应
        $format = empty(request('format')) ? 'JSON' : $format=request('format');
        if (strtoupper($format) == 'XML') {
            $content = $this->xmlEncode($this->get_responseData());
            header('Content-Type: text/xml');
            echo $content;
            exit();
        }
        // JSON响应
        header('Content-Type: application/json');
        echo json_encode($this->get_responseData());
        exit();
    }

    /* *
     * 获取数据
     *
     * @param  string $key
     * @return mixed
     * */
    public function get($key = null) {
        if(is_string($key))
            return isset($this->data[$key]) ? $this->data[$key] : null;
        return $this->get_responseData();
    }

    // 封装响应数据
    private function get_responseData()
    {
        $this->data['errs'] = $this->data_errs;
        $response = array(
            'err' => $this->api_err,
            'msg' => $this->api_msg,
            'data' => $this->data
        );
        return $response;
    }

    /* *
     * xml编码
     *
     * @param array $data 数据
     * return string
     * */
    private function xmlEncode($data = array()) {
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .= $this->xmlToEncode($data);
        $xml .= "</root>";
        return $xml;
    }

    private function xmlToEncode($data) {
        $xml = "";
        foreach($data as $key => $value) {
            $attr = "";
            if(is_numeric($key)) {
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