<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/29/2019
 * Time: 01:28
 */
namespace phpchassis\http\webservices\rest;

/**
 * Class BaseApi
 * @package phpchassis\http\rest
 */
abstract class BaseApi implements RestApiInterface {

    const TOKEN_BYTE_SIZE = 16;

    /**
     * @var
     */
    protected $registeredKeys;

    /**
     * BaseApi constructor.
     * @param $registeredKeys
     * @param $tokenField
     */
    public function __construct($registeredKeys, $tokenField) {
        $this->registeredKeys = $registeredKeys;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateToken() {
        return bin2hex(random_bytes(self::TOKEN_BYTE_SIZE));    // TODO Integrate with PhpChassis Storage manager
    }

    /**
     * Retrieve resource representation
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract public function get(RestRequest $request, RestResponse $response);

    /**
     * Updates existing resource
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract public function put(RestRequest $request, RestResponse $response);

    /**
     * Create new (subordinate) resources
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract public function post(RestRequest $request, RestResponse $response);

    /**
     * Deletes a given  resource
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract public function delete(RestRequest $request, RestResponse $response);

    /**
     * Authenticates a request
     * @param Request $request
     * @return mixed
     */
    abstract public function authenticate(RestRequest $request): bool;
}