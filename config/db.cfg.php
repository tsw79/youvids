<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/4/2019
 * Time: 08:06
 */

// @TODO Set the following vars:
//      ENV_DEV = 'dev'
//      ENV_TEST = 'test'
//      ENV_PROD = 'prod'

return [
  /*
   * Development database
   */
  "dev" => [
    "type"      => "mysql",
    "extension" => "pdo",
    //"class"     => 'data/Connection',
    "username"  => "youvids-usr",
    "password"  => "youvids123",
    "charset"   => "utf8",
    "dsn"       => "mysql:host=localhost;dbname=youvids",
  ],
  /*
   * Test database
   */
  "tests" => [
    "host"      => "",
    "dbname"    => "",
    "username"  => "",
    "password"  => ""
  ],
  /*
   * Production database
   */
  "prod" => [
    "host"      => "",
    "dbname"    => "",
    "username"  => "",
    "password"  => ""
  ],
  /*
   * Redis in-memory database
   */
  'redis' => [
    'class'    => 'yii/redis/Connection',
    'hostname' => 'localhost',
    'port'     => 6379,
    'database' => 0,
  ],
];