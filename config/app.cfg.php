<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/4/2019
 * Time: 08:25
 */
return [
  /*
    * application-wide configs
    */
  "appName"   => "YouVids",
  "basePath"  => dirname(__DIR__),
  "timeZone"  =>  "Asia/Riyadh",
  "security"  => [
    /*
     * Set the encryption method, possible values:
     *      secret_key
     *      envelope
     */
    "encryption"    =>  "secret_key",
    /*
     * Set the password hashing algorithm, possible values:
     *      argon2          pbkdf2          sha1
     *      bcrypt          portable        sha256
     *      extended_des    scrypt          sha512
     *      md5
     */
    "password"      =>  "bcrypt"
  ],
  "storage" => [
    "cache"         => "",
    "session"       => "php"
  ],
  /*
   * encoder configs
   */
  "encoder" => require(__DIR__ . '/encoder.cfg.php'),
  /*
   * params configs
   */
  "params" => require(__DIR__ . '/params.cfg.php'),
  /*
   * data configs
   */
  "db" => require(__DIR__ . '/db.cfg.php'),
  /*
   * log config
   */
  "log" => require(__DIR__ . '/log.cfg.php'),
  /*
   * cache config
   */
  "cache" => require(__DIR__ . '/cache.cfg.php'),
  /*
   * acl config
   */
  "access_control" => null,
];