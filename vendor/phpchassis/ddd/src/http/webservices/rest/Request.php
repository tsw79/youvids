<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 19:42
 */
namespace phpchassis\http\webservices\rest;

/**
 * Class Request can accept parameters when we wish to generate a request, or, alternatively, populate properties with
 * incoming request information when implementing a server that accepts requests.
 *
 * @package phpchassis\http\rest
 */
class Request extends BaseHttpRest {

    /**
     * Request constructor.
     * @param null $uri
     * @param null $method
     * @param array|null $headers
     * @param array|null $data
     * @param array|null $cookies
     */
    public function __construct($uri = null, $method = null, array $headers = null, array $data = null, array $cookies = null) {

        if (!$headers) {
            $this->headers = $_SERVER ?? array();                                   // TODO Integrate with PhpChassis Middleware
        }
        else {
            $this->headers = $headers;
        }

        if (!$uri) {
            $this->uri = $this->headers['PHP_SELF'] ?? '';                          // TODO Integrate with PhpChassis Middleware
        }
        else {
            $this->uri = $uri;
        }

        if (!$method) {
            $this->method = $this->headers['REQUEST_METHOD'] ?? self::METHOD_GET;   // TODO Integrate with PhpChassis Middleware
        }
        else {
            $this->method = $method;
        }

        if (!$data) {
            $this->data = $_REQUEST ?? array();                                     // TODO Integrate with PhpChassis Middleware
        }
        else {
            $this->data = $data;
        }

        if (!$cookies) {
            $this->cookies = $_COOKIE ?? array();                                   // TODO Integrate with PhpChassis Middleware
        }
        else {
            $this->cookies = $cookies;
        }
        $this->setTransportOrigin();
    }
}