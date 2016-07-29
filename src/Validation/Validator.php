<?php

namespace Me\Validation;


/**
 * 数据验证器
 *
 * 1. 支持的验证规则有 required|mobile|email|min:|max:|numeric|integer|date|ip|url|activeUrl
 *
 * 待开发：
 * 1. $rules 和 $msg 中的数据 支持 . 语法
 * 2. 验证参数获取器
 * 3. 开发验证器：
 *    img
 *    file
 *    table
 *
 *
 *
 *
 *
 *
 * @author AlpFish 2016/7/28 23:52
 * @eg.

    $validate = validate(
        request()->only('username', 'mobile'),
        [
            'username' => 'required|min:4|max:12',
            'mobile' => 'required|mobile'
        ],
        [
            'username:required' => '需要填写会员名。',
            'username:min' => '会员名长度需要大于4个字符。',
            'username:max' => '会员名长度需要小于12个字符。',
            'mobile:required' => '需要填写手机号。',
            'mobile:mobile' => '手机号格式不正确。'
        ]
    );

    if (!$validate->success)
        api('data')->errs($validate->errors)->response();
 */

class Validator
{
    //待验数据
    private $data;

    //待验规则
    private $rules;

    //指定错误信息
    private $msg = [];

    //验证成功标志
    public $success = true;

    //验证错误数据
    public $errors = [];

    //验证错误翻译
    private $translator = [];

    public function __construct($data, $rules, $msg = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->msg = $msg;
        $this->translator = require __DIR__.'/translator.php';
        $this->fire();
    }

    public function fire()
    {

        // $attribute    待验证条目属性
        // $rule         条目验证规则集
        foreach ($this->rules as $attribute => $rule) {

            // $item     条目规则集
            foreach (explode('|', $rule) as $item) {

                // $detial 具体规则方法
                $detial = explode(':', $item);

                // 方法存在则验证
                if (method_exists($this, $detial[0])) {
                    if ( count( $detial ) > 1 ) { // 传参验证
                        $reason = $this->$detial[0]($this->data[$attribute], $detial[1]);
                    } else { // 无参验证
                        $reason = $this->$item($this->data[$attribute]);
                    }
                    //获取失败信息
                    if ( $reason !== true ) {
                        //统一条目失败信息
                        if (isset($this->msg[$attribute])) {
                            $this->errors[$attribute][] = $this->msg[$attribute];
                        //单独验证器失败信息
                        } elseif (isset($this->msg[$attribute. ':' .$detial[0]])) {
                            $this->errors[$attribute][] = $this->msg[$attribute. ':' .$detial[0]];
                        //默认失败信息
                        } else {
                            $this->errors[$attribute][] = str_replace(':attribute', $attribute, $reason);
                        }
                    }
                } else {
                    exit('后台错误, Validator 使用了不存在的验证方法：'.$detial[0]);
                }
            }
        }
        if ( count($this->errors) ) {
            $this->success = false;
        }

        return $this;
    }

    //必须验证器
    protected function required($value)
    {
        return !$value ? $this->translator['required'] : true;
    }

    //Email验证器
    protected function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : $this->translator['email'];
    }

    //手机号验证器
    protected function mobile($value)
    {
        return preg_match('/^1[34578][0-9]{9}$/', $value) ? true : $this->translator['mobile'];
    }

    //最小长度验证器
    protected function min($value, $min)
    {
        return mb_strlen($value, 'UTF-8') >= $min ? true : str_replace(':min', $min, $this->translator['min']);
    }

    //最大长度验证器
    protected function max($value, $max)
    {
        return mb_strlen($value, 'UTF-8') <= $max ? true : str_replace(':max', $max, $this->translator['max']);
    }

    //数字验证器
    protected function numeric($value)
    {
        return is_numeric($value) ? true : $this->translator['numeric'];
    }

    //整数验证器
    protected function integer($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false ? true : $this->translator['integer'];
    }

    //URL验证器

    protected function url($value)
    {
        /*
         * This pattern is derived from Symfony\Component\Validator\Constraints\UrlValidator (2.7.4)
         * (c) Fabien Potencier <fabien@symfony.com> http://symfony.com
         */
        $pattern = '~^
            ((aaa|aaas|about|acap|acct|acr|adiumxtra|afp|afs|aim|apt|attachment|aw|barion|beshare|bitcoin|blob|bolo|callto|cap|chrome|chrome-extension|cid|coap|coaps|com-eventbrite-attendee|content|crid|cvs|data|dav|dict|dlna-playcontainer|dlna-playsingle|dns|dntp|dtn|dvb|ed2k|example|facetime|fax|feed|feedready|file|filesystem|finger|fish|ftp|geo|gg|git|gizmoproject|go|gopher|gtalk|h323|ham|hcp|http|https|iax|icap|icon|im|imap|info|iotdisco|ipn|ipp|ipps|irc|irc6|ircs|iris|iris.beep|iris.lwz|iris.xpc|iris.xpcs|itms|jabber|jar|jms|keyparc|lastfm|ldap|ldaps|magnet|mailserver|mailto|maps|market|message|mid|mms|modem|ms-help|ms-settings|ms-settings-airplanemode|ms-settings-bluetooth|ms-settings-camera|ms-settings-cellular|ms-settings-cloudstorage|ms-settings-emailandaccounts|ms-settings-language|ms-settings-location|ms-settings-lock|ms-settings-nfctransactions|ms-settings-notifications|ms-settings-power|ms-settings-privacy|ms-settings-proximity|ms-settings-screenrotation|ms-settings-wifi|ms-settings-workplace|msnim|msrp|msrps|mtqp|mumble|mupdate|mvn|news|nfs|ni|nih|nntp|notes|oid|opaquelocktoken|pack|palm|paparazzi|pkcs11|platform|pop|pres|prospero|proxy|psyc|query|redis|rediss|reload|res|resource|rmi|rsync|rtmfp|rtmp|rtsp|rtsps|rtspu|secondlife|service|session|sftp|sgn|shttp|sieve|sip|sips|skype|smb|sms|smtp|snews|snmp|soap.beep|soap.beeps|soldat|spotify|ssh|steam|stun|stuns|submit|svn|tag|teamspeak|tel|teliaeid|telnet|tftp|things|thismessage|tip|tn3270|turn|turns|tv|udp|unreal|urn|ut2004|vemmi|ventrilo|videotex|view-source|wais|webcal|ws|wss|wtai|wyciwyg|xcon|xcon-userid|xfire|xmlrpc\.beep|xmlrpc.beeps|xmpp|xri|ymsgr|z39\.50|z39\.50r|z39\.50s))://                                 # protocol
            (([\pL\pN-]+:)?([\pL\pN-]+)@)?          # basic auth
            (
                ([\pL\pN\pS-\.])+(\.?([\pL]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                    |                                              # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                 # a IP address
                    |                                              # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]  # a IPv6 address
            )
            (:[0-9]+)?                              # a port (optional)
            (/?|/\S+)                               # a /, nothing or a / with something
        $~ixu';

        return preg_match($pattern, $value) === 1 ? true : $this->translator['url'];
    }

    //ip验证器
    protected function ip($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false ? true : $this->translator['ip'];
    }

    //图片验证器
    /*protected function validateImage($value)
    {
        todo
    }*/

    //日期时间验证器
    protected function date($value)
    {
        if ($value instanceof \DateTime) {
            return true;
        }

        if (strtotime($value) === false) {
            return $this->translator['date'];
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']) ? true : $this->translator['date'];
    }

    public function __call($method, $parameters)
    {
        throw new \UnexpectedValueException("Validate rule [$method] does not exist!");
    }
}