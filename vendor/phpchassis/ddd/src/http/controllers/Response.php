<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/15/2019
 * Time: 17:47
 */
namespace phpchassis\http\controllers;

use phpchassis\http\FlashMessage;

/**
 * Class (Controller) Response
 * @package phpchassis\http\controllers
 */
class Response {

    /**
     * Holds a single instance of Response
     * @var Response $instance
     */
    private static $instance = null;

    /**
     * Represent parts of the FormRequest/ServerRequest
     * @var object
     */
    private $request;

    /**
     * Additional (data) paramaters
     * @var array|object
     */
    private $params;

    /**
     * Submitted (form) data that has been validated and/or filtered
     * @var array
     */
    private $data;

    /**
     * Holds the value of the requested property set by the magic __get method
     * @var string
     */
    private $__getProperty;

    /**
     * Creates a new instance of Response
     * @param object $request
     * @param array $data
     * @param array|null $params
     * @return Response
     */
    public static function create(object $request, array $data, array $params = null) : self {
        $self = (new Response())
            ->request($request)
            ->data($data)
            ->params($params);
        return $self;
    }

    /**
     * Returns the value of a given attribute set in $params
     * @param string $param
     * @return mixed
     */
    public function param(string $key) {
        return $this->params[$key] ?? '';
    }

    public function flash(): string {
        return FlashMessage::instance()->messages();
    }

    /**
     * Getter/Setter for request
     * @param object $request
     * @return object
     */
    public function request(object $request = null) {
        if(null === $request) {
            return $this->request;
        }
        else {
            $this->request = $request;
            return $this;
        }
    }

    /**
     * Getter/Setter for params
     * @param array $params
     * @return array|object
     */
    public function params(array $params = null) {
        if(null === $params) {
            return $this->params;
        }
        else {
            $this->params = $params;
            return $this;
        }
    }

    /**
     * Getter/Setter for data
     * @param array $data
     * @return array
     */
    public function data(array $data = null) {
        if(null === $data) {
            return $this->data;
        }
        else {
            $this->data = $data;
            return $this;
        }
    }

    /**
     * Returns the value of WEB_ROOT
     * @return string
     */
    public function webroot(): string {
        return WEB_ROOT;
    }

    // ------------------------------------------ __get magic method builder -------------------------------------------

    /**
     * BUILDER: Sets the value of the given attribute's name as set in the $data property
     * @param $propertyName
     * @return mixed
     */
    public function __get(string $attr): self {
        $this->__getProperty = $attr;
        return $this;
    }

    /**
     * Returns the value of the $data's key by a pre-set property set by the magic __get method
     * @return string
     */
    public function get(): string {
        return $this->data[$this->__getProperty] ?? '';
    }

    /**
     * Returns the error message(s) from the validation results by a pre-set property set by the magic __get method
     * @param string $param
     * @return string
     */
    public function error(): string {

        if (!empty($this->request->results) && isset($this->request->results[$this->__getProperty])) {
            return implode('<br>', array_values($this->request->results[$this->__getProperty]->messages())) ?? '';
        }
        return '';
    }

    // -----------------------------------------------------------------------------------------------------------------

    /**
     * DatabaseConnection constructor.
     *  Prevent creating multiple instances due to "private" constructor
     */
    private function __construct() {}

    /**
     * Prevent the instance from being cloned
     */
    private function __clone() {}

    /**
     * Prevent from being unserialized
     */
    private function __wakeup () {}
}