<?php

namespace Me\Contracts\Config;

interface Config
{
    /**
     * 加载配置文件
     *
     * @param  string $path
     *
     * @return $this
     */
    public function load($path);

    /**
     * 获取配置项值
     *
     * @param string $name 配置项名，默认返回全部
     * @param string $default 默认值，值不存在时返回
     *
     * @return mixed
     *
     * @author AlpFish 2016/7/25 15:40
     */
    public function get($name = null, $default = null);

    /**
     * 获取全部配置
     *
     * @return $this->data
     *
     * @author AlpFish 2016/7/25 15:43
     */
    public function all();
}
