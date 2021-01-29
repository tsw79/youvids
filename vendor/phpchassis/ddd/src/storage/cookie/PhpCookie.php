<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/4/2019
 * Time: 22:13
 */
namespace phpchassis\storage\cokkie;

/**
 * Class PhpCookie
 * @package phpchassis-ddd\storage\cokkie
 */
class PhpCookie {

  /**
   * open
   */
    public function open($name, $value) {
        $expire = time() + 4800;    // Expires after a day
        setcookie($name, $value, $expire);
    }

    /**
     * Retrieves a value from the cookie
     */
    public function get($name) {
        return $_COOKIE[$name];
    }

    /**
     * Removes a value from cookie
     */
    public function remove($name) {
        $expired = time() - 4800;    // Expires before today
        setcookie($name, '', $expired);
    }
}