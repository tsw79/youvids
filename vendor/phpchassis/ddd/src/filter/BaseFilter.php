<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 06:15
 */
namespace phpchassis\filter;

/**
 * Class BaseFilter
 * @package phpchassis\filter
 */
class BaseFilter {

    const BAD_CALLBACK = "Must implement CallbackInterface";
    const DEFAULT_SEPARATOR = "<br>" . PHP_EOL;
    const MISSING_MESSAGE_KEY = "item.missing";
    const DEFAULT_MESSAGE_FORMAT = "%20s : %60s";
    const DEFAULT_MISSING_MESSAGE = "Item Missing";

    /**
     * Used for message display in conjunction with filtering and validation messages
     * @var
     */
    protected $separator;

    /**
     * @var
     */
    protected $missingMessage;

    /**
     * Represents the array of callbacks that perform filtering and validation
     * @var array
     */
    protected $callbacks;

    /**
     * Maps data fields to filters and/or validators
     * @var array
     */
    protected $assignments;

    /**
     * Populated by the filtering or validation operation
     * @var array (Result)
     */
    protected $results = array();

    /**
     * AbstractFilter constructor.
     * @param array $callbacks
     * @param array $assignments
     * @param null $separator
     * @param null $message
     */
    public function __construct(array $callbacks, array $assignments, $separator = null, $message = null) {

        $this->callbacks($callbacks);
        $this->assignments($assignments);
        $this->separator($separator ?? self::DEFAULT_SEPARATOR);
        $this->missingMessage($message ?? self::DEFAULT_MISSING_MESSAGE);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getOneCallback($key) {
        return $this->callbacks[$key] ?? null;
    }

    public function hasCallback($key): bool {
        return true === isset($this->callbacks[$key]);
    }

    /**
     * Checks to see if the callback implements CallbackInterface
     * @param $key
     * @param $item
     */
    public function setOneCallback($key, $item) {

        if ($item instanceof CallbackInterface) {
            $this->callbacks[$key] = $item;
        }
        else {
            throw new \UnexpectedValueException(self::BAD_CALLBACK);
        }
    }

    /**
     * @param $key
     */
    public function removeOneCallback($key) {

        if (isset($this->callbacks[$key])) {
            unset($this->callbacks[$key]);
        }
    }

    /**
     * @return array
     */
    public function getItemsAsArray(): array {

        $return = array();

        if ($this->results) {
            foreach ($this->results as $key => $item) {
                $return[$key] = $item->item();
            }
        }
        return $return;
    }

    /**
     * @return array|\Generator
     */
    public function getMessages() {

        if ($this->results) {

            foreach ($this->results as $key => $item) {
                if ($item->messages()) {
                    yield from $item->messages();
                }
            }
        }
        else {
            return array();
        }
    }

    /**
     * @param int $width
     * @param null $format
     * @return string
     */
    public function getMessageString($width = 80, $format = null) {

        if (!$format) {
            $format = self::DEFAULT_MESSAGE_FORMAT . $this->separator;
        }

        $output = '';

        if ($this->results) {

            foreach ($this->results as $key => $value) {

                $messages = $value->messages();

                if ($messages) {
                    foreach ($messages as $message) {
                        $output .= sprintf( $format, $key, trim($message));
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Getter/Setter for missingMessage
     * @param string $missingMessage
     * @return string
     */
    public function missingMessage(string $missingMessage = null) {

        if(null === $missingMessage) {
            return $this->missingMessage;
        }
        else {
            $this->missingMessage = $missingMessage;
        }
    }

    /**
     * Getter/Setter for separator
     * @param string $separator
     * @return string
     */
    public function separator(string $separator = null) {

        if(null === $separator) {
            return $this->separator;
        }
        else {
            $this->separator = $separator;
        }
    }

    /**
     * Getter/Setter for assignments
     * @param array $assignments
     * @return array
     */
    public function assignments(array $assignments = null) {

        if(null === $assignments) {
            return $this->assignments;
        }
        else {
            $this->assignments = $assignments;
        }
    }

    /**
     * Getter/Setter for callbacks
     * @param array $callbacks
     * @return array
     */
    public function callbacks(array $callbacks = null) {

        if(null === $callbacks) {
            return $this->callbacks;
        }
        else {
            foreach ($callbacks as $key => $item) {
                $this->setOneCallback($key, $item);
            }
        }
    }

    /**
     * Getter/Setter for results
     * @param array $results
     * @return array
     */
    public function results(array $results = null) {

        if(null === $results) {
            return $this->results;
        }
        else {
            $this->results = $results;
        }
    }
}