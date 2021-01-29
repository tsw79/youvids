<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/1/2019
 * Time: 23:19
 */
namespace phpchassis\cryptography\hash\base;

/**
 * Class BaseHashAlgo
 * @package phpchassis-ddd\cryptography\hash\dto
 */
abstract class BaseHash {

    protected $salt;

    public function __construct() { }

    public function salt($salt = null) {

        // Getter
        if($salt === null) {
            return $this->$salt;
        }
        // Setter
        else {
            $this->$salt = $salt;
        }
    }
}