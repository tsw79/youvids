<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 16:41
 */
return [
  /**
   * Log type:  Specifies whether logging will be done to a file, database, etc.
   *
   *      type = php/file/db
   */
  "type"  =>  "file",
  /**
   * Log level:  Specifies the logging level
   *
   *      level = emergency/alert/critical/notice/debug/info/warning/error
   */
  "level" =>  "debug",
  /**
   * Name of logging file - set this if you're using a file log
   *  If no name has been specified, this will default to the name set for log level, e.g. debug.php
   *
   * Note: absolute path, C:/wwwroot/php_test/logs/debug.log
   */
  "filename"  =>  "C:\\wamp64\\www\\dev.youvids\\runtime\\logs\\error.log",
  /**
   * Database info - set this if you're using a db to store logs
   *
   *      db = string (specifies the table_name of the current db application's settings
   *      db = []     (specifies the new database to connect to)
   */
  "db" =>  ""
];