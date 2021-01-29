<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 19:52
 */
namespace phpchassis\http\webservices\rest\client;

use phpchassis\http\webservices\rest\ {Request as RestRequest, Received, RestClientAdapterInterface};

/**
 * Class Stream (Adapter)
 * @package phpchassis\http\rest\client
 */
class Stream implements RestClientAdapterInterface {

    const BYTES_TO_READ = 4096;

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
                if ($data) {
                    $request->uri($request->uri() . '?' . $data);
                }
                $resource = fopen($request->uri(), 'r');
                break;
            case RestRequest::METHOD_POST :
                $opts = [
                    $request->transport() =>
                        [
                            'method' => Request::METHOD_POST,
                            'header' => Request::HEADER_CONTENT_TYPE . ': ' . Request::CONTENT_TYPE_FORM_URL_ENCODED,
                            'content' => $data
                        ]
                ];
                $resource = fopen($request->uri(), 'w',
                    stream_context_create($opts));
                break;
        }
        return self::results($received, $resource);
    }

    /**
     * Retrieves and packages results into a Received object
     * @param Received $received
     * @param $resource
     * @return Received
     */
    protected static function results(Received $received, $resource): Received {

        $received->metaData(stream_get_meta_data($resource));
        $data = $received->metaDataByKey('wrapper_data');

        if (!empty($data) && is_array($data)) {

            foreach($data as $item) {
                if (preg_match('!^HTTP/\d\.\d (\d+?) .*?$!', $item, $matches)) {
                    $received->headerByKey('status', $matches[1]);
                }
                else {
                    list($key, $value) = explode(':', $item);
                    $received->headerByKey($key, trim($value));
                }
            }
        }

        $payload = '';

        while (!feof($resource)) {
            $payload .= fread($resource, self::BYTES_TO_READ);
        }
        if ($received->headerByKey(Received::HEADER_CONTENT_TYPE)) {
            switch (true) {
                case stripos($received->headerByKey(Received::HEADER_CONTENT_TYPE), Received::CONTENT_TYPE_JSON) !== false :
                    $received->data(json_decode($payload));
                    break;
                default :
                    $received->data($payload);
                    break;
            }
        }
        return $received;
    }
}