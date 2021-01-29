<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/29/2019
 * Time: 01:12
 */
namespace phpchassis\http\webservices\rest;

use phpchassis\http\webservices\rest\ {
    Request as RestRequest,
    Response as RestResponse
};

/**
 * Class Server
 * @package phpchassis\http\rest
 */
class Server {

    /**
     * @var ApiInterface
     */
    protected $api;

    /**
     * Server constructor.
     * @param ApiInterface $api
     */
    public function __construct(RestApiInterface $api) {
        $this->api = $api;
    }

    /**
     * Captures raw input, which is assumed to be in JSON format
     */
    public function listen(): void {

        $request = new RestRequest();
        $response = new RestResponse($request);
        $getPost = $_REQUEST ?? array();

        $jsonData = json_decode(file_get_contents('php://input'),true);
        $jsonData = $jsonData ?? array();
        $request->data(array_merge($getPost,$jsonData));

        // Authenticate
        if (!$this->api->authenticate($request)) {
            $response->status(RestRequest::STATUS_401);
            echo $this->api::ERROR;
            exit;
            // TODO Integrate with PhpChassis Log manager
        }

        $id = $request->data()[$this->api::ID_FIELD] ?? null;

        switch (strtoupper($request->method())) {

            case RestRequest::METHOD_POST :
                $this->api->post($request, $response);
                break;

            case RestRequest::METHOD_PUT :
                $this->api->put($request, $response);
                break;

            case RestRequest::METHOD_DELETE :
                $this->api->delete($request, $response);
                break;

            case RestRequest::METHOD_GET :
            default :
                // return all if no params
                $this->api->get($request, $response);
        }

        // package the response and send it out, JSON-encoded!
        $this->processResponse($response);
        echo json_encode($response->data());
    }

    /**
     * Sets headers and makes sure the result is packaged as an RestResponse object
     * @param $response
     */
    protected function processResponse(RestResponse $response) {

        $headers = $response->headers();

        if ($headers) {
            foreach ($headers as $key => $value) {
                header($key . ': ' . $value, true, $response->status());
            }
        }

        header(RestRequest::HEADER_CONTENT_TYPE . ': ' . RestRequest::CONTENT_TYPE_JSON, true);
        $cookies = $response->cookies();

        if ($cookies) {
            foreach ($cookies as $key => $value) {
                setcookie($key, $value);                // TODO Integrate with PhpChassis Storage manager
            }
        }
    }
}