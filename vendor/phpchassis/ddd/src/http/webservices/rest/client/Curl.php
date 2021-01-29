<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 20:14
 */
namespace phpchassis\http\webservices\rest\client;

use phpchassis\http\webservices\rest\ {Request as RestRequest, Received, RestClientAdapterInterface};

/**
 * Class Curl (Adapter) makes use of PHP's cURL extension.
 * @package phpchassis\http\rest\client
 */
class Curl implements RestClientAdapterInterface {

    /**
     * Sends the request to an external web service
     * @param Request $request
     * @return Received
     */
    public static function send(RestRequest $request): Received {

        $data = $request->dataEncoded();
        $received = new Received();

        switch ($request->method()) {

            case RestRequest::METHOD_GET :

                $uri = ($data)
                    ? $request->uri() . '?' . $data
                    : $request->uri();

                $options = [
                    CURLOPT_URL            => $uri,
                    CURLOPT_HEADER         => 0,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 4
                ];
                break;

            case RestRequest::METHOD_POST :

                $options = [
                    CURLOPT_POST           => 1,
                    CURLOPT_HEADER         => 0,
                    CURLOPT_URL            => $request->uri(),
                    CURLOPT_FRESH_CONNECT  => 1,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FORBID_REUSE   => 1,
                    CURLOPT_TIMEOUT        => 4,
                    CURLOPT_POSTFIELDS     => $data
                ];
                break;
        }

        $ch = curl_init();
        curl_setopt_array($ch, ($options));

        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }

        $received->metaData(curl_getinfo($ch));
        curl_close($ch);

        return self::results($received, $result);
    }

    /**
     * Retrieves and packages results into a Received object
     * @param Received $received
     * @param $payload
     * @return Received
     */
    protected static function results(Received $received, $payload): Received {

        $type = $received->metaDataByKey('content_type');

        if ($type) {
            switch (true) {
                case (false !== stripos($type, Received::CONTENT_TYPE_JSON)):
                    $received->data(json_decode($payload));
                    break;
                default:
                    $received->data($payload);
                    break;
            }
        }
        return $received;
    }

}