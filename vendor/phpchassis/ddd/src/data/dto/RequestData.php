<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 01:08
 */
namespace phpchassis\data\dto;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestData
 *  Base class for RequestData DTO's
 *
 *      Note:
 *      -----
 *      1.) With this approach, we can decouple the high-level policies from the low-level implementation details. The
 *      communication between the delivery mechanism and the Domain is carried by data structures called DTOs.
 *
 *      2.) The View is a layer that can both send and receive messages from the Model layer and/or from the Controller
 *      layer. Its main purpose is to represent the Model to the user at the UI level, as well as to refresh the
 *      representation in the UI each time the Model is updated. Generally speaking, the View layer receives an object -
 *      often a Data Transfer Object (DTO) instead of instances of the Model layer — thereby gathering all the needed
 *      information to be successfully represented.
 *
 * @package namespace PhpChassis\data\dto;
 */
abstract class RequestData {

    /**
     * @var
     */
    public $params = null;

    /**
     * Returns the request as an array
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Add additional data by passing paramaters here
     * @param array $params
     * @return $this
     */
    public function withParams(array $params) {

        $this->params = $params;
        return $this;
    }

    /**
     * Type cast the params attribute from an array to an stdClass object
     * @return object
     */
    public function paramsToObject(): self {

        $this->params = (object) $this->params;
        return $this;
    }

    /**
     * Use this magic method to return the value that was set by the withParams method.
     * __get magic implementation for client code to read as: $clientObj->property
     *
     * @param $propertyName
     * @return mixed
     */
    public function __get($propertyName) {
        return $this->params;
    }
}
