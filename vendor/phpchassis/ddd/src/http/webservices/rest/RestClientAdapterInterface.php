<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 20:28
 */
namespace phpchassis\http\webservices\rest;

use phpchassis\http\webservices\rest\ {Request as RestRequest, Received};

/**
 * Interface RestClientAdapterInterface
 * @package phpchassis\http\rest
 */
interface RestClientAdapterInterface {

    /**
     * Sends the request to an external web service
     * @param Request $request
     * @return Received
     */
    public static function send(RestRequest $request): Received;

    /**
     * Retrieves and packages results into a Received object
     * @param Received $received
     * @param $payload
     * @return Received
     */
    //protected static function results(Received $received, $payload): Received;
}