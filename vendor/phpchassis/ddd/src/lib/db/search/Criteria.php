<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 07:15
 */
namespace phpchassis\lib\db\search;

/**
 * Class Criteria
 *  Holds the search criteria
 *
 * @package phpchassis-ddd\lib\db\search
 */
class Criteria {

    private $key;
    private $item;
    private $operator;

    public function __construct($key, $operator, $item = null) {

        $this->key  = $key;
        $this->operator = $operator;
        $this->item = $item;
    }

    /**
     * Getter/Setter for key
     *
     * @param null $key
     * @return string
     */
    public function key($key = null) {
        if($key === null) {
            return $this->key;
        }
        else {
            $this->key = $key;
        }
    }

    /**
     * Getter/Setter for item
     *
     * @param null $item
     * @return string
     */
    public function item($item = null) {
        if($item === null) {
            return $this->item;
        }
        else {
            $this->item = $item;
        }
    }

    /**
     * Getter/Setter for operator
     *
     * @param null $operator
     * @return string
     */
    public function operator($operator = null) {
        if($operator === null) {
            return $this->operator;
        }
        else {
            $this->operator = $operator;
        }
    }
}