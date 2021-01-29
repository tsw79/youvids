<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/10/2019
 * Time: 22:14
 */
namespace phpchassis\http\middleware;

use Psr\Http\Message\ {MessageInterface, StreamInterface, UriInterface};

/**
 * Class (Http) Message makes use of the PSR7 standard interfaces to build a Message middleware.
 *
 * @package phpchassis-ddd\middleware
 */
class Message implements MessageInterface {

    /**
     * @var $body
     */
    protected $body;

    /**
     * @var $version
     */
    protected $version;

    /**
     * @var array $httpHeaders
     */
    protected $httpHeaders = array();

    /**
     * The getBody() method that represents a StreamInterface instance.
     *
     * @return Stream
     */
    public function getBody() {

        if (!$this->body) {
            $this->body = new Stream(self::DEFAULT_BODY_STREAM);
        }
        return $this->body;
    }

    /**
     * A companion method rather, withBody(), returns the current Message instance and allows us to overwrite the
     * current value of body
     *
     * @param StreamInterface $body
     * @return $this
     * @throws InvalidArgumentException
     */
    public function withBody(StreamInterface $body) {

        if (!$body->isReadable()) {
            throw new \InvalidArgumentException(self::ERROR_BODY_UNREADABLE);
        }
        $this->body = $body;
        return $this;
    }


    /**
     * findHeader() method (not directly defined by MessageInterface)
     * that locates a header using stripos():
     *
     * @param $name
     * @return bool
     */
    protected function findHeader($name) {

        $found = false;
        foreach (array_keys($this->getHeaders()) as $header) {
            if (stripos($header, $name) !== false) {
                $found = $header;
                break;
            }
        }
        return $found;
    }

    /**
     * This method is designed to populate the $httpHeaders property.
     * This property is assumed to be an associative array where the key is the
     * header, and the value is the string representing the header value. If there is more
     * than one value, additional values separated by commas are appended to the string.
     *      apache_request_headers() PHP function from the Apache
     *      extension produces headers if they are not already available in $httpHeaders:
     *
     * @return array
     */
    protected function getHttpHeaders() {

        if (!$this->httpHeaders) {
            if (function_exists("apache_request_headers")) {
                $this->httpHeaders = apache_request_headers();
            }
            else {
                $this->httpHeaders = $this->altApacheReqHeaders();
            }
        }
        return $this->httpHeaders;
    }


    /**
     * altApacheReqHeaders method as an alternative
     *  If apache_request_headers() is not available (that is, the Apache extension is not enabled), use this method instead.
     *
     * @return array
     */
    protected function altApacheReqHeaders() {

        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (stripos($key, "HTTP_") !== false) {
                $headerKey = str_ireplace("HTTP_", "", $key);
                $headers[$this->explodeHeader($headerKey)] = $value;
            }
            elseif (stripos($key, "CONTENT_") !== false) {
                $headers[$this->explodeHeader($key)] = $value;
            }
        }
        return $headers;
    }

    /**
     * @param $header
     * @return mixed
     */
    protected function explodeHeader($header) {
        $headerParts = explode("_", $header);
        $headerKey = ucwords(implode(" ", strtolower($headerParts)));
        return str_replace(" ", "-", $headerKey);
    }


    /**
     * Implementing getHeaders() (required in PSR-7) loops through the $httpHeaders property
     */
    public function getHeaders() {
        foreach ($this->getHttpHeaders() as $key => $value) {
            header($key . ": " . $value);
        }
    }

// -------------------------------------------------------------------------------------------------
// These are a series of with methods designed to overwrite or replace headers.
// Since there can be many headers, we also have a method that adds to the existing set of headers.
// -------------------------------------------------------------------------------------------------

    /**
     * withHeader
     *  Note: findHeader() allow for case-insensitive handling of headers
     * 
     * @param string $name
     * @param string|\string[] $value
     * @return $this
     */
    public function withHeader($name, $value) {

        $found = $this->findHeader($name);
        if ($found) {
            $this->httpHeaders[$found] = $value;
        }
        else {
            $this->httpHeaders[$name] = $value;
        }
        return $this;
    }

    /**
     * withAddedHeader
     *  Note: findHeader() allow for case-insensitive handling of headers
     *
     * @param string $name
     * @param string|\string[] $value
     * @return $this
     */
    public function withAddedHeader($name, $value) {

        $found = $this->findHeader($name);
        if ($found) {
            $this->httpHeaders[$found] .= $value;
        }
        else {
            $this->httpHeaders[$name] = $value;
        }
        return $this;
    }

    /**
     * This method is used to remove a header instance.
     *  Note: findHeader() allow for case-insensitive handling of headers
     *
     * @param string $name
     * @return $this
     */
    public function withoutHeader($name) {

        $found = $this->findHeader($name);
        if ($found) {
            unset($this->httpHeaders[$found]);
        }
        return $this;
    }

// --------------------------------------------------------------------------------------
// As per PSR-7, these are a series of useful header-related methods to confirm a header
// exists, retrieve a single header line, and retrieve a header in array form
// --------------------------------------------------------------------------------------

    /**
     * Checks if header exists
     *
     * @param string $name
     * @return mixed
     */
    public function hasHeader($name) {
        return boolval($this->findHeader($name));
    }

    /**
     * Returns the header line
     *
     * @param string $name
     * @return mixed|string
     */
    public function getHeaderLine($name) {

        $found = $this->findHeader($name);
        if ($found) {
            return $this->httpHeaders[$found];
        }
        else {
            return "";
        }
    }

    /**
     * Returns the header
     *
     * @param string $name
     * @return array
     */
    public function getHeader($name) {

        $line = $this->getHeaderLine($name);
        if ($line) {
            return explode(",", $line);
        }
        else {
            return array();
        }
    }

    /**
     * The getHeadersAsString method produces a single header string with the headers separated by \r\n for direct use
     * with PHP stream contexts.
     *
     * @return string
     */
    public function getHeadersAsString() {

        $output = "";
        $headers = $this->getHeaders();

        if ($headers && is_array($headers)) {
            foreach ($headers as $key => $value) {
                if ($output) {
                    $output .= "\r\n" . $key . ": " . $value;
                }
                else {
                    $output .= $key . ": " . $value;
                }
            }
        }
        return $output;
    }

    /**
     * Version handling:  According to PSR-7, the return value for the protocol version (that is, HTTP/1.1) should
     * only be the numerical part. For this reason, the onlyVersion() method is provided that strips off any non-digit
     * character, allowing periods.
     *
     * @return null
     */
    public function getProtocolVersion() {
        if (!$this->version) {
            $this->version = $this->onlyVersion($_SERVER["SERVER_PROTOCOL"]);
        }
        return $this->version;
    }

    /**
     * BUILDER: Adds protocol version to object
     *
     * @param string $version
     * @return $this
     */
    public function withProtocolVersion($version) {

        $this->version = $this->onlyVersion($version);
        return $this;
    }

    /**
     * This method is strips off any non-digit characters, allowing periods.
     * @param $version
     * @return null
     */
    protected function onlyVersion($version) {
        if (!empty($version)) {
            return preg_replace("/[^0-9\.]/", "", $version);
        }
        else {
            return null;
        }
    }
}