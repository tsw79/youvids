<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 19:49
 */
namespace phpchassis\http\webservices\rest;

/**
 * Class Received repackages data received from an external web service.
 * @package phpchassis\http\rest
 */
class Received extends BaseHttpRest {

    /**
     * Received constructor.
     * @param null $uri
     * @param null $method
     * @param array|null $headers
     * @param array|null $data
     * @param array|null $cookies
     */
    public function __construct($uri = null, $method = null, array $headers = null, array $data = null, array $cookies = null) {

        $this->uri = $uri;
        $this->method = $method;
        $this->headers = $headers;
        $this->data = $data;
        $this->cookies = $cookies;
        $this->setTransportOrigin();
    }
}