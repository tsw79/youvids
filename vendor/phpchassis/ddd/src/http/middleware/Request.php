<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/10/2019
 * Time: 22:41
 */
namespace phpchassis\http\middleware;

use InvalidArgumentException;
use Psr\Http\Message\ {RequestInterface, StreamInterface, UriInterface};

/**
 * Class (Http) Request is an in-bound request, i.e. a class to represent an outgoing request a client will
 * make to a server.
 *
 * @package phpchassis-ddd\middleware
 */
class Request extends Message implements RequestInterface {

    /**
     * @var string $uri
     */
    protected $uri;

    /**
     * @var HTTP $method
     */
    protected $method;

    /**
     * @var UriInterface instance
     */
    protected $uriObj;

    /**
     * (Http) Request constructor.
     * @param null $uri
     * @param null $method
     * @param StreamInterface|null $body
     * @param null $headers
     * @param null $version
     */
    public function __construct($uri = null, $method = null, StreamInterface $body = null, $headers = null, $version = null) {

        $this->uri = $uri;
        $this->body = $body;
        $this->method = $this->checkMethod($method);
        $this->httpHeaders = $headers;
        $this->version = $this->onlyVersion($version); // sanitize the version
    }

    /**
     * Checks method
     * @param $method
     * @return mixed
     */
    protected function checkMethod($method) {

        if (!$method === null) {

            if (!in_array(strtolower($method), Constants::HTTP_METHODS)) {
                throw new InvalidArgumentException(Constants::ERROR_HTTP_METHOD);
            }
        }
        return $method;
    }

    /**
     * Returns the target of the Request
     * @return null|string
     */
    public function getRequestTarget() {
        return $this->uri ?? Constants::DEFAULT_REQUEST_TARGET;
    }

    /**
     * BUILDER: Sets the request target of the Uri object
     * @param $requestTarget
     * @return $this
     */
    public function withRequestTarget($requestTarget) {

        $this->uri = $requestTarget;
        $this->getUri();
        return $this;
    }

    /**
     * Returns the method
     * @return mixed|HTTP
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Returns true if it's a POST Request
     * @return bool
     */
    public function isPostMethod(): bool {
        return $this->method == Constants::METHOD_POST;
    }

    /**
     * Returns true if it's a GET Request
     * @return bool
     */
    public function isGetMethod(): bool {
        return $this->method == Constants::METHOD_GET;
    }

    /**
     * BUILDER:  Sets and checks the method for the object
     * @param $method
     * @return $this
     */
    public function withMethod($method) {
        // We use checkMethod() - used in the constructor as well - to ensure the method matches those we plan to support.
        $this->method = $this->checkMethod($method);
        return $this;
    }

    /**
     * This method retains the original request string in the $uri property and the newly parsed Uri instance in $uriObj.
     * @return Uri|UriInterface
     */
    public function getUri() {

        if (!$this->uriObj) {
            $this->uriObj = new Uri($this->uri);
        }
        return $this->uriObj;
    }

    /**
     * BUILDER: With() method for the Uri
     *
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return $this
     */
    public function withUri(UriInterface $uri, $preserveHost = false) {

        if ($preserveHost) {
            $found = $this->findHeader(Constants::HEADER_HOST);

            if (!$found && $uri->getHost()) {
                $this->httpHeaders[Constants::HEADER_HOST] = $uri->getHost();
            }
        }
        elseif ($uri->getHost()) {
            $this->httpHeaders[Constants::HEADER_HOST] = $uri->getHost();
        }
        $this->uri = $uri->__toString();
        return $this;
    }
}