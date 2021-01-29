<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/30/2019
 * Time: 04:21
 */
namespace phpchassis\lib\traits;

use phpchassis\lib\collections\Collection;

/**
 * Class Commons
 * @package phpchassis-ddd\traits
 */
trait PhpCommons {

    /**
     * Checks if a key exists in an array by combining PHP built-in array_key_exists and isset functions
     *
     *      array_key_exists - will definitely tell you if a key exists in an array
     *      isset            - will only return true if the key/variable exists and is not null
     *
     *      Example:
     *      -------
     *          $a = array('key1' => 'foobar', 'key2' => null);
     *
     *          isset($a['key1']);             // true
     *          array_key_exists('key1', $a);  // true
     *
     *          isset($a['key2']);             // false
     *          array_key_exists('key2', $a);  // true
     *
     * @param $key
     * @param array $arr
     * @return bool
     */
    public function array_key_isset($key, array $arr): bool {
        return isset($arr[$key]) || array_key_exists($key, $arr);
    }

    /**
     * PHP get_object_vars counterpart
     * Usage:
     *          $a = new A();
     *          $vars = array('one' => 234, 'two' => 2);
     *          set_object_vars($a, $vars);
     *
     * @param $object
     * @param array $vars
     */
    public function set_object_vars($object, array $vars) {

        $has = get_object_vars($object);

        foreach ($has as $name => $oldValue) {
            $object->$name = isset($vars[$name]) ? $vars[$name] : null;
        }
    }

    public function isCollection($object): bool {
        return true === ($object instanceof Collection);
    }
}