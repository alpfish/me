<?php

namespace Me\Config;

use Me\Contracts\Config\Config as ConfigInterface;

class Config implements ConfigInterface
{
    /**
     * 存储配置数据
     *
     * @var array
     */
    protected $data = array();

    /**
     * 单例模式
     */
    protected static $self;

    /* *
     * @return self
     */
    public static function getInstance()
    {
        if (self::$self) return self::$self;
        return self::$self = new self();
    }

    /**
     * 加载配置文件
     *
     * @param  string $path
     *
     * @return $this
     */
    public function load($path)
    {
        if (is_file($path)) $this->data = array_merge($this->data, (array)require $path);
        return $this;
    }

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
    public function get($name = null, $default = null)
    {
        $config = $this->data;
        if (is_null($name)) return $config;
        if (is_string($name)) {
            $name = explode('.', $name);
            switch (count($name)) {
                case 1:
                    return isset($config[$name[0]]) ? $config[$name[0]] : $default;
                case 2:
                    return isset($config[$name[0]][$name[1]]) ? $config[$name[0]][$name[1]] : $default;
                case 3:
                    return isset($config[$name[0]][$name[1]][$name[2]]) ? $config[$name[0]][$name[1]][$name[2]] : $default;
            }
        }
        return is_null($default) ? null : $default;
    }

    /**
     * 获取全部配置
     *
     * @return $this->data
     *
     * @author AlpFish 2016/7/25 15:43
     */
    public function all()
    {
        return $this->data;
    }
}
