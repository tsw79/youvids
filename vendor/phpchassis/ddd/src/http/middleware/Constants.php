<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/10/2019
 * Time: 22:05
 */
namespace phpchassis\http\middleware;

/**
 * Class (Http) Constants
 * @package phpchassis\middleware
 */
class Constants {

    /**
     * Host header
     */
    const HEADER_HOST   = "Host";

    /**
     * Content type
     */
    const HEADER_CONTENT_TYPE = "Content-Type";

    /**
     * Content length
     */
    const HEADER_CONTENT_LENGTH = "Content-Length";

    /**
     * GET method
     */
    const METHOD_GET    = "get";

    /**
     * POST method
     */
    const METHOD_POST   = "post";

    /**
     * PUT method
     */
    const METHOD_PUT    = "put";

    /**
     * DELETE method
     */
    const METHOD_DELETE = "delete";

    /**
     * List of Http methods
     */
    const HTTP_METHODS  = [
        "get",
        "put",
        "post",
        "delete"
    ];

    /**
     * List of standard ports
     */
    const STANDARD_PORTS = [
        "ftp"   => 21,
        "ssh"   => 22,
        "http"  => 80,
        "https" => 443
    ];

    /**
     * Content type for Form encode
     */
    const CONTENT_TYPE_FORM_ENCODED = "application/x-www-form-urlencoded";

    /**
     * Content type for Multi form
     */
    const CONTENT_TYPE_MULTI_FORM = "multipart/form-data";

    /**
     * Content type JSON
     */
    const CONTENT_TYPE_JSON = "application/json";

    /**
     * Content type for Hal JSON
     */
    const CONTENT_TYPE_HAL_JSON = "application/hal+json";

    /**
     * Default status code
     */
    const DEFAULT_STATUS_CODE = 200;

    /**
     * Default body stream
     */
    const DEFAULT_BODY_STREAM = "php://input";

    /**
     * Default Request target
     */
    const DEFAULT_REQUEST_TARGET = "/";

    /**
     * Read mode
     */
    const MODE_READ = "r";

    /**
     * Write mode
     */
    const MODE_WRITE = "w";

    /**
     * @TODO: not all error constants are shown to conserve space
     *
     * Bad error
     */
    const ERROR_BAD = "ERROR: ";

    /**
     * Bad directory error
     */
    const ERROR_BAD_DIR = "ERROR: Bad directory: ";

    /**
     * Unknown error
     */
    const ERROR_UNKNOWN = "ERROR: unknown";

    /**
     * UInvalid status
     */
    const ERROR_INVALID_STATUS = "ERROR: Invalid status";

    /**
     * Invalid upload
     */
    const ERROR_INVALID_UPLOADED = "ERROR: Invalid upload";
}