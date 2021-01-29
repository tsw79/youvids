<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/28/2019
 * Time: 18:18
 */
namespace phpchassis\data\valueobject;

/**
 * Interface ValueObjectInterface
 * @package phpchassis-ddd\data\valueobject
 */
interface ValueObjectInterface {

    /*
        https://medium.com/funeralzone/a-better-way-of-writing-value-objects-in-php-d4e224de133
        https://github.com/funeralzone/valueobjects/blob/master/README.md

        - Whether the value of this ValueObject can be considered equivalent to another ValueObject

            public function equals(ValueObjectInterface $object): bool;


        - fromNative and toNative are essentially for serialisation.
        - For getting your value objects in and out of your application (e.g. persisting and retrieving from a database)

            public static function fromNative(ValueObjectInterface $native): string;
            public function toNative(): ValueObjectInterface;
     */


    /**
     * Creates a new instance of ValueObjectInterface
     * @param $value
     * @return ValueObjectInterface
     */
//    public static function create($value);

    /**
     * Returns true if the two value objects are the same
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equalsTo(ValueObjectInterface $object): bool;

    /**
     * Returns the value of the Value Object
     * @return string
     */
//    public function get();
}