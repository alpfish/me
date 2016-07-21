<?php
namespace Alpfish\Me\Api;

use Alpfish\Me\Contracts\Api\Data as DataContract;

/*--------------------------------------------------------------------------------------------
 *    简单数据封装 和 基本响应
 *--------------------------------------------------------------------------------------------
 *
 * 1. 全局使用data()帮助函数
 * 2. 支持方法链, response()放最后
 * 3. 错误数据响应类型：
 *    (1)调用错误
 *    (2)应用错误
 * 4. 响应数据固定格式：
 *    {
 *      "err": 0, //调用错误
 *      "msg": '',
 *      "data":[
 *          "errs": [ ] //应用错误
 *      ],
 *      "help": ''
 *    }
 * 5. 格式约定
 *    后端正确 && 前端正确则返回"data"区数据， 否则返回相关前后端错误信息
 *    (1)后端错误："data"外，系统响应，格式不固定
 *    (2)前端错误："data"外，后端指定，格式不固定，sendBadFoo()装填，建议有"help"相关帮助文档链接
 *    (3)用户错误："data"内，后端指定，格式严格固定，setErr($k,$v)装填或sendErr($k, $v)装填加响应，$k为错误对象(名词),$v为错误信息
 *    (4)正常数据："data"内，set()装填，
 * 6. 用户错误信息键为"err"数组
 * 7. "data"区数据为数组形式，所有数据必须有$key,$value键值对
 * 8. 默认响应格式为JSON，请求中携带$format参数指定响应格式，支持JSON和XML
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