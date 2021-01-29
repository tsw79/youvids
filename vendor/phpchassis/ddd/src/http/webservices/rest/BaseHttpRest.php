<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 18:50
 */
namespace phpchassis\http\webservices\rest;

/**
 * Class BaseHttpRest
 * @package phpchassis\http\rest
 */
abstract class BaseHttpRest {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_FORM_URL_ENCODED = 'application/x-www-form-urlencoded';
    const HEADER_CONTENT_TYPE = 'Content-Type';
    const TRANSPORT_HTTP = 'http';
    const TRANSPORT_HTTPS = 'https';
    const STATUS_200 = '200';
    const STATUS_401 = '401';
    const STATUS_500 = '500';

    /**
     * i.e. http://xxx.com/yyy
     * @var string
     */
    protected $uri;

    /**
     * i.e. GET, PUT, POST, DELETE
     * @var string
     */
    protected $method;

    /**
     * HTTP headers
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $cookies;

    /**
     * Information about the transmission
     * @var array
     */
    protected $metaData;

    /**
     * i.e. http or https
     * @var string
     */
    protected $transport;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param $uri
     * @param array|null $params
     */
    public function setUri($uri, array $params = null): void {

        $this->uri = $uri;
        $first = true;

        if ($params) {
            $this->uri .= '?' . http_build_query($params);
        }
    }

    /**
     * Encodes $data
     * @return string
     */
    public function dataEncoded(): string {
        return http_build_query($this->data());
    }

    /**
     * Set transport based on its original request
     * @param null $transport
     */
    public function setTransportOrigin($transport = null) {

        if ($transport) {
            $this->transport = $transport;
        }
        else {
            if (substr($this->uri, 0, 5) == self::TRANSPORT_HTTPS) {
                $this->transport = self::TRANSPORT_HTTPS;
            }
            else {
                $this->transport = self::TRANSPORT_HTTP;
            }
        }
    }

    /**
     * Gets or Sets the $metaData by a given key
     * @param string $key
     * @param string $value
     * @return string
     */
    public function metaDataByKey($key, $value = null) {

        if(null === $value) {
            return $this->metaData[$key] ?? null;
        }
        else {
            $this->metaData[$key] = $value;
        }
    }

    /**
     * Gets or Sets the $data by a given key
     * @param string $key
     * @param string $value
     * @return string
     */
    public function dataByKey($key, $value = null) {

        if(null === $value) {
            return $this->data[$key] ?? null;
        }
        else {
            $this->data[$key] = $value;
        }
    }

    /**
     * Gets or Sets the $header by a given key
     * @param string $key
     * @param string $value
     * @return string
     */
    public function headerByKey($key, $value = null) {

        if(null === $value) {
            return $this->headers[$key] ?? null;
        }
        else {
            $this->headers[$key] = $value;
        }
    }

    /**
     * Getter/Setter for uri
     * @param string $uri
     * @return string
     */
    public function uri(string $uri = null) {

        if(null === $uri) {
            return $this->uri;
        }
        else {
            $this->uri = $uri;
        }
    }

    /**
     * Getter/Setter for method
     * @param string $method
     * @return string
     */
    public function method(string $method = null) {

        if(null === $method) {
            return $this->method;
        }
        else {
            $this->method = $method;
        }
    }

    /**
     * Getter/Setter for headers
     * @param array $headers
     * @return array
     */
    public function headers(array $headers = null) {

        if(null === $headers) {
            return $this->headers;
        }
        else {
            $this->headers = $headers;
        }
    }

    /**
     * Getter/Setter for metaData
     * @param array $metaData
     * @return array
     */
    public function metaData(array $metaData = null) {

        if(null === $metaData) {
            return $this->metaData;
        }
        else {
            $this->metaData = $metaData;
        }
    }

    /**
     * Getter/Setter for transport
     * @param string $transport
     * @return string
     */
    public function transport(string $transport = null) {

        if(null === $transport) {
            return $this->transport;
        }
        else {
            $this->transport = $transport;
        }
    }

    /**
     * Getter/Setter for data
     * @param array $data
     * @return array
     */
    public function data($data = null) {

        if(null === $data) {
            return $this->data;
        }
        else {
            $this->data = $data;
        }
    }
}