<?php
namespace Alpfish\Me\Contracts\Api;

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
     * @param  mixed  $value
     * @return $this
     * */
    public function err($key, $value = null);

    /* *
     * 设置调用错误数据
     *
     * @param  int $code
     * @param  string  $msg
     * @return $this
     * */
    public function api_err($msg, $code = 400);

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