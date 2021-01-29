<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 05:56
 */
namespace phpchassis\filter;

/**
 * Class Result
 *  The primary function of the Result class will be to hold:
 *      1.) $item value, which would be the filtered value or a boolean result of validation
 *      2.) $messages, will hold an array of messages populated during the filtering or validation operation.
 *  In the constructor, the value supplied for $messages is formulated as an array.

 * @package phpchassis\filter
 */
class Result {

    /**
     * @var (mixed) filtered data | (bool) result of validation
     */
    private $item;

    /**
     * @var array [(string) message, (string) message ]
     */
    private $messages = array();

    /**
     * Result constructor.
     * @param $item
     * @param $messages
     */
    public function __construct($item, $messages) {

        $this->item = $item;
        if (is_array($messages)) {
            $this->messages = $messages;
        }
        else {
            $this->messages = [$messages];
        }
    }

    /**
     * Merges this Result instance with another
     * @param Result $result
     */
    public function mergeResults(Result $result) {
        $this->item = $result->item;
        $this->mergeMessages($result);
    }

    /**
     * @param Result $result
     */
    public function mergeMessages(Result $result) {
        if (isset($result->messages) && is_array($result->messages)) {
            $this->messages = array_merge($this->messages, $result->messages);
        }
    }

    /**
     * Merges validation results
     * @param Result $result
     */
    public function mergeValidationResults(Result $result) {
        if ($this->item === true) {
            $this->item = (bool) $result->item;
        }
        $this->mergeMessages($result);
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
     * Getter/Setter for messages
     * @param array $messages
     * @return string
     */
    public function messages($messages = null) {
        if($messages === null) {
            return $this->messages;
        }
        else {
            $this->messages = $messages;
        }
    }
}