<?php

namespace App\Helpers;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
  protected function connect()
  {
    global $config;

    $capsule = new Capsule;

    $capsule->addConnection([
      'driver'    => 'mysql',
      'host'      => $config['database']['host'],
      'database'  => $config['database']['database'],
      'username'  => $config['database']['username'],
      'password'  => $config['database']['password'],
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => $config['database']['prefix'],
    ]);

    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();

    return $capsule;
  }

  protected function build_schema()
  {
    $this->connect()->schema()->dropIfExists('presets');

    $this->connect()->schema()->create('presets', function ($table) {
      $table->string('name', 128)->unique();
      $table->string('address', 128);
      $table->string('display_name', 128)->nullable();
      $table->string('font', 128)->nullable();
      $table->string('color', 7)->nullable();
      $table->integer('background')->nullable();
      $table->integer('type')->nullable();
    });

    $this->connect()->table('presets')->insert([
      'name' => 'hypixel',
      'address' => 'mc.hypixel.net',
      'font' => 'minecraftia',
      'color' => '#e0b450',
      'background' => 5,
      'type' => 1
    ]);
  }
}