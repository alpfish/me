<?php
namespace Alpfish\Me\Contracts\Api;

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

interface Data
{
    /* *
     * @return self
     * 在帮助函数中实现：return \Alp\Api\Data\Data::getInstance();
     * */
    public static function getInstance();

    /* *
    * 设置应用数据(支持数组)
    *
    * @param  string|array $key
    * @param  mixed  $value
    * @return $this
    * */
    public function set($key, $value = null);

    /* *
     * 设置应用错误数据
     *
     * @param  string|array $key
     * @param  string  $value
     * @return $this
     * */
    public function errs($key, $value = null);

    /* *
     * 设置响应状态信息
     *
     * @param  int $code
     * @param  string  $msg
     * @return $this
     * */
    public function status($code, $msg);

    /* *
     * Api 响应
     *
     * @return Response
     * */
    public function response();

    /* *
     * 获取数据
     *
     * @param  string $key
     * @return mixed
     * */
    public function get($key = null);
}