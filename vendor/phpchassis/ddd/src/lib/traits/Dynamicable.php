<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/17/2019
 * Time: 09:59
 *  
 *    https://freek.dev/857-a-trait-to-dynamically-add-methods-to-a-class
 *    
 */
namespace phpchassis\lib\traits;

/**
 * Trait Dynamicable
 * @package phpchassis\traits
 */
trait Dynamicable {

    /**
     * @var array
     */
    protected static $dynamics = array();

    /**
     * Registers a custom dynamic callback function
     * @param string $name
     * @param $callback
     */
    public static function register(string $name, $callback) {
        self::$dynamics[$name] = $callback;
    }

    /**
     * Adds another object into the class.
     * @param $addin
     * @throws \ReflectionException
     */
    public static function addin($addin) {

        $methods = (new \ReflectionClass($mixin))->getMethods(
            \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED
        );
        foreach ($methods as $method) {
            $method->setAccessible(true);
            self::register($method->name, $method->invoke($addin));
        }
    }

    /**
     * Retruns true if a given dynamic (method) exists
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool {
        return isset(self::$dynamics[$name]);
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {

        if (!self::has($method)) {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }
        $dynamic = static::$dynamics[$method];

        if ($dynamic instanceof Closure) {
            return call_user_func_array($dynamic->bindTo($this, self::class), $parameters);
        }
        return call_user_func_array($dynamic, $parameters);
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters) {

        if (!static::has($method)) {
            throw new \BadMethodCallException("Method {$method} does not exist.");
        }
        $dynamic = self::$dynamics[$method];

        if ($dynamic instanceof Closure) {
            return call_user_func_array(\Closure::bind($dynamic, null, self::class), $parameters);
        }
        return call_user_func_array($dynamic, $parameters);
    }
}