<?php
namespace Me\Contracts\Api;

interface Router
{
    /**
     * Api 路由器
     *
     * @return api()->response();
     *
     * @author AlpFish 2016/7/24 10:35
     */
    public static function run();
}