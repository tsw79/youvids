<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/29/2019
 * Time: 01:31
 */
namespace phpchassis\http\webservices\rest;

use phpchassis\http\webservices\rest\ {Request as RestRequest, Response as RestResponse};

/**
 * Interface RestApiInterface
 * @package phpchassis\http\rest
 */
interface RestApiInterface {

    /**
     * Retrieve resource representation
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function get(RestRequest $request, RestResponse $response);

    /**
     * Updates existing resource
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function put(RestRequest $request, RestResponse $response);

    /**
     * Create new (subordinate) resources
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function post(RestRequest $request, RestResponse $response);

    /**
     * Deletes a given  resource
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function delete(RestRequest $request, RestResponse $response);

    /**
     * Authenticates a request
     * @param Request $request
     * @return mixed
     */
    public function authenticate(RestRequest $request): bool;
}