<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/28/2019
 * Time: 00:56
 */
namespace phpchassis\lib\collections;

/**
 * Class CollectionSpl
 *
 * @package phpchassis-ddd\lib\collections
 */
class CollectionSpl extends \ArrayObject {

    public function offsetSet($index, $newval) {

        if (!is_int($newval)) {
            throw new \InvalidArgumentException("Must be int");
        }
        parent::offsetSet($index, $newval);
    }
}