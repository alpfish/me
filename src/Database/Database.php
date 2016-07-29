<?php
namespace Me\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

/*
|--------------------------------------------------------------------------
| 项目使用 Eloquent ORM
|--------------------------------------------------------------------------
|
| 1. 选择 Laravel 的 Eloquent 作为项目的 ORM，不仅能使代码更加优雅，而且有完善的文档。
| 2. 引入下面两个依赖包(alpfish/me 的 composer.json 中已引入)：
|    illuminate/database, illuminate/events
| 3. 项目模型继承 Illuminate\Database\Eloquent\Model 即可。具体使用见官方文档。
|
*/
class Database
{

    /**
     * 单例模式
     */
    protected static $self;

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (static::$self) {
            return static::$self;
        }
        static::$self = new self();

        static::bootDatabase();

        return  static::$self;
    }

    protected function bootDatabase()
    {
        $capsule = new Capsule;
        $capsule->addConnection(me_config('database'));

        /**
         * 使用查询构建器
         *
         */
        $capsule->setAsGlobal();
        // 别名使用
        class_alias('Illuminate\Database\Capsule\Manager', 'Capsule');
        // 引入使用
        // use Illuminate\Database\Capsule\Manager as DB;
        // $data = DB::table('goods_sku')->get();

        /**
         * 启动 Eloquent ORM
         *
         */

        // Eloquent 模型设置事件调度，不用可注释掉
        // use Illuminate\Events\Dispatcher;
        // use Illuminate\Container\Container;
        // $capsule->setEventDispatcher(new Dispatcher(new Container));

        $capsule->bootEloquent();
    }
}