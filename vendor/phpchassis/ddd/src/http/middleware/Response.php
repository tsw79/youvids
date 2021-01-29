<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/10/2019
 * Time: 23:49
 */
namespace phpchassis\http\middleware;

use phpchassis\http\middleware\Constants;
use phpchassis\lib\traits\PhpCommons;
use Psr\Http\Message\ {ResponseInterface, StreamInterface};

/**
 * Class Response
 * @package PhpChassis\middleware
 */
class Response extends Message implements ResponseInterface {

    use PhpCommons;

    /**
     * @var $statusCode
     */
    protected $statusCode;

    /**
     * Response constructor.
     *  The constructor is not defined by PSR-7, but it's provided for convenience, allowing a developer to create a
     *  Response instance with all parts intact. Use methods from Message and constants from the Constants class to
     *  verify the arguments.
     *
     * @param null $statusCode
     * @param StreamInterface|null $body
     * @param null $headers
     * @param null $version
     */
    public function __construct($statusCode = null, StreamInterface $body = null, $headers = null, $version = null) {

        $this->body = $body;
        $this->status["code"] = $statusCode ?? Constants::DEFAULT_STATUS_CODE;
        $this->status["reason"] = (null !== $statusCode) ? HttpStatusCode::toText($statusCode) : '';
        $this->httpHeaders = $headers;
        $this->version = $this->onlyVersion($version);

        if ($statusCode) {
            $this->setStatusCode();
        }
    }

    /**
     * Sets the HTTP status code, irrespective of any headers
     */
    public function setStatusCode() {
        http_response_code($this->getStatusCode());
    }

    /**
     * It is of interest to obtain the status code using the following method
     *
     * @return mixed
     */
    public function getStatusCode() {
        return $this->status["code"];
    }

    /**
     * BUILDER: Sets the status code and returns the current instance
     *
     * @param $statusCode
     * @param string $reasonPhrase
     * @return $this
     * @throws InvalidArgumentException
     */
    public function withStatus($statusCode, $reasonPhrase = "") {

        if(!HttpStatusCode::exists($statusCode)) {
            throw new \InvalidArgumentException(Constants::ERROR_INVALID_STATUS);
        }
        $this->status["code"] = $statusCode;
        $this->status["reason"] = HttpStatusCode::toText($statusCode); // $this->status["reason"] = ($reasonPhrase) ? Constants::STATUS_CODES[$statusCode] : null;
        $this->setStatusCode();
        return $this;
    }

    /**
     * This method returns the reason for the HTTP status, which is a short text phrase, in this example, based
     * on RFC 7231.
     *
     * @return string
     */
    public function getReasonPhrase() {
        return $this->status["reason"]
            ?? HttpStatusCode::toText($this->status["code"])
            ?? "";
    }

    /**
     * Returns true if the status code is greater or equal than 200 AND less than 300
     * @return bool
     */
    public function statusCodeIsWithinSuccessRange(): bool {
        return true === ($this->getStatusCode() >= HttpStatusCode::OK && $this->getStatusCode() < HttpStatusCode::MULTIPLE_CHOICES);
    }

    /**
     * @alias isStatusCodeWithinSuccessRange()
     * @return bool
     */
    public function isSuccessfull(): bool {
        return $this->statusCodeIsWithinSuccessRange();
    }
}