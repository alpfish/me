<?php
namespace Alpfish\Me\Contracts\Api;

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
 *      "err": '调用错误',
 *      "msg": '',
 *      "data":[
 *          "err": [ ] //应用错误
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