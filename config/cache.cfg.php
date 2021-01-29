<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/5/2019
 * Time: 23:54
 */
return [
  /**
   * FileCache Configuration settings
   */
  "file" => [
    "class" => "/src/lib/FileCache.php",
    "dir"   => RUNTIME_DIR . "/cache/file/"
    //'store' => 'memcached',
    //'expire' => 600,
    //'prefix' => 'cache-prefix',
  ],
  /**
   * MysqlCache Configuration settings
   */
  "sqlite" => [
    "dir"   => RUNTIME_DIR . "/cache/sqlite/"
  ],
];