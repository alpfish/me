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
     * 获取配置
     *
     * @param string $name    配置项名
     * @param string $default 默认值，配置选项不存在的话默认值将会被指定并返回
     *
     * @return mixed
     *
     * @author AlpFish 2016/7/25 15:40
     */
    public function get($name = null, $default = null)
    {
        if (is_string($name)) {
            $name = explode('.', $name);
            switch (count($name)) {
                case 1:
                    if (!isset($this->data[$name[0]])) $this->data[$name[0]] = $default;
                    return $this->data[$name[0]];
                case 2:
                    if (!isset($this->data[$name[0]][$name[1]])) $this->data[$name[0]][$name[1]] = $default;
                    return $this->data[$name[0]][$name[1]];
                case 3:
                    if (!isset($this->data[$name[0]][$name[1]][$name[2]])) $this->data[$name[0]][$name[1]][$name[2]] = $default;
                    return $this->data[$name[0]][$name[1]][$name[2]];
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
