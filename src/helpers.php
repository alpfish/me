<?php

if ( ! function_exists('request'))
{
    function request($name = null)
    {
        if(is_string($name)) {
            //暂时返回经由海盗系统处理过的 $_GET 数据（已包括$_POST）
            return $_GET[$name];
        }
        return null;
    }
}