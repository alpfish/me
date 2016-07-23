# 介绍

Laravel 官方 Illuminate Database 文档。

介绍如何引入 illuminate/database, 以便项目能够使用数据库迁移、查询构建器、和 Eloquent ORM 。

alpfish/me 包 composer.json 文件中已引入相关依赖(illuminate/database, illuminate/events)，并且已经在 bootstrap.php 文件中进行了配置。

只需要在项目模型继承 Illuminate\Database\Eloquent\Model 类便可使用。

## Illuminate Database

The Illuminate Database component is a full database toolkit for PHP, providing an expressive query builder, ActiveRecord style ORM, and schema builder. It currently supports MySQL, Postgres, SQL Server, and SQLite. It also serves as the database layer of the Laravel PHP framework.

### Usage Instructions

First, create a new "Capsule" manager instance. Capsule aims to make configuring the library for usage outside of the Laravel framework as easy as possible.

```PHP
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
```

> `composer require "illuminate/events"` required when you need to use observers with Eloquent.

Once the Capsule instance has been registered. You may use it like so:

**Using The Query Builder**

```PHP
$users = Capsule::table('users')->where('votes', '>', 100)->get();
```
Other core methods may be accessed directly from the Capsule in the same manner as from the DB facade:
```PHP
$results = Capsule::select('select * from users where id = ?', array(1));
```

**Using The Schema Builder**

```PHP
Capsule::schema()->create('users', function($table)
{
    $table->increments('id');
    $table->string('email')->unique();
    $table->timestamps();
});
```

**Using The Eloquent ORM**

```PHP
class User extends Illuminate\Database\Eloquent\Model {}

$users = User::where('votes', '>', 1)->get();
```

For further documentation on using the various database facilities this library provides, consult the [Laravel framework documentation](http://laravel.com/docs).
