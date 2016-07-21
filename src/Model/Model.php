<?php
namespace Alpfish\Me\Model;
/**
 * Auth: AlpFish.
 * Date: 2016/7/20 18:12
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Container\Container;

class Model
{
    static function show()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'haidao',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'hd_',
        ]);

        //$capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

//        $users = Capsule::table('member')->where('id', '=', 1)->get();
        $users = $capsule->table('member')->find(1);

        ddd($users);
    }
}