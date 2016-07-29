<?php

namespace Me\Contracts\Config;

interface Config
{
    /**
     * 获取配置
     *
     * @param string $name 配置项名
     * @param string $default 默认值，配置选项不存在的话默认值将会被指定并返回
     *
     * @return mixed
     */
    public function get($name = null, $default = null);

    /**
     * 获取全部配置
     *
     * @return $this->data
     */
    public function all();
}
