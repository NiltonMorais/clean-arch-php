<?php

namespace App\Infra\DataBase;

use Illuminate\Database\Capsule\Manager as Capsule;

class DataBase
{
    public static function bootEloquent() {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'mysql',
            'database' => 'clean_arch',
            'username' => 'root',
            'password' => 'root',
            'port' => 'root',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->bootEloquent();
    }
}
