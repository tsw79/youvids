<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/4/2019
 * Time: 18:25
 */
namespace phpchassis\storage\base;

/**
 * Interface SessionInterface
 * @package phpchassis-ddd\storage\dto
 */
interface SessionInterface {

    public function open();
    public function close();
    public function get(string $name);
    public function set(string $name, $value);
    public function read();
    public function write();
    public function destroy();
    //public function garbage();
    public function remove($name);
    public function isActive() : bool;  // Checks if a session is already open
    public function has($name) : bool;  // Checks if a session variable exists
}