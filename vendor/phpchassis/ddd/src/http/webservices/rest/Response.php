<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/29/2019
 * Time: 01:00
 */
namespace phpchassis\http\webservices\rest;

use phpchassis\http\webservices\rest\Request as RestRequest;

/**
 * Class Response
 * @package phpchassis\http\rest\
 */
class Response extends BaseHttpRest {

    /**
     * Response constructor.
     * @param Request|null $request
     * @param null $status
     * @param null $contentType
     */
    public function __construct(RestRequest $request = null, $status = null, $contentType = null) {

        if ($request) {
            $this->uri = $request->uri();
            $this->data = $request->data();
            $this->method = $request->method();
            $this->cookies = $request->cookies();
            $this->setTransportOrigin();
        }

        $this->processHeaders($contentType);

        if ($status) {
            $this->status($status);
        }
    }

    /**
     * @param $contentType
     */
    protected function processHeaders($contentType) {

        $this->headerByKey(
            self::HEADER_CONTENT_TYPE,
            (!$contentType) ? self::CONTENT_TYPE_JSON : $contentType
        );
    }

    /**
     * Getter/Setter for status
     * @param array $status
     * @return array
     */
    public function status($status = null) {

        if(null === $status) {
            return $this->status;
        }
        else {
            $this->status = $status;
        }
    }
}