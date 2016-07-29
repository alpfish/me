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
    protected static $data = array();

    /**
     * 单例模式
     */
    protected static $self;

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$self) {
            return self::$self;
        }
        
        return self::$self = new self();
    }

    public function __construct() {
        $configFiles = [
            ab_path('/vendor/alpfish/me/config/config.php'),
            ab_path('/config/me.php')
        ];
        foreach ($configFiles as $file) {
            if (is_file($file)) self::$data = array_merge(self::$data, (array)require $file);
        }
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
            if(!data_get(self::$data, $name, $default)) {
                $this->seachLoad($name);
            }
            return data_get(self::$data, $name, $default);
        }

        return self::$data;
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
        return self::$data;
    }

    /**
     * 搜索加载单独配置文件
     *
     * @param  string $key
     *
     * @return $this
     */
    private function seachLoad($key)
    {
        $name = explode('.', $key);

        $configFiles = [
            ab_path('/vendor/alpfish/me/config/'.$name[0] . '.php'),
            ab_path('/config/'.$name[0] . '.php')
        ];
        foreach ($configFiles as $file) {
            if (is_file($file)) {
                $config[$name[0]] = (array)require $file;
                self::$data = array_merge(self::$data, $config);
            }
        }
    }
}
