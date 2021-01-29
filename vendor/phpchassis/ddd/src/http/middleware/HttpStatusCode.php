<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/22/2019
 * Time: 14:04
 */
namespace phpchassis\http\middleware;

/**
 * Class HttpStatusCode
 *      This class represents a list of HTTP Statud Codes!
 *      Ref: https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 *
 *          Status Code synopsis:
 *              1xx - Informational response
 *              2xx - Success
 *              3xx - Redirection
 *              4xx - Client errors
 *              5xx - Server errors
 *
 * @package phpchassis\http\middleware
 */
abstract class HttpStatusCode {

    public const
        /**
         * To have a server check the request's headers, a client must send Expect: 100-continue as a header in its initial request
         * and receive a 100 Continue status code in response before sending the body. If the client receives an error
         * code such as 403 (Forbidden) or 405 (Method Not Allowed) then it shouldn't send the request's body. The response
         * 417 Expectation Failed indicates that the request should be repeated without the Expect header as it indicates
         * that the server doesn't support expectations (this is the case, for example, of HTTP/1.0 servers).
         */
        HTTP_CONTINUE =                         100,
        /**
         * The requester has asked the server to switch protocols and the server has agreed to do so.
         */
        SWITCHING_PROTOCOLS =                   101,
        /**
         * A WebDAV request may contain many sub-requests involving file operations, requiring a long time to complete the
         * request. This code indicates that the server has received and is processing the request, but no response is
         * available yet. This prevents the client from timing out and assuming the request was lost.
         */
        PROCESSING =                            102,    // RFC2518
        /**
         * Used to return some response headers before final HTTP message.
         */
        EARLY_HINTS =                           103,    // RFC8297
        /**
         * Standard response for successful HTTP requests. The actual response will depend on the request method used.
         * In a GET request, the response will contain an entity corresponding to the requested resource.
         * In a POST request, the response will contain an entity describing or containing the result of the action.
         */
        OK =                                    200,
        /**
         * The request has been fulfilled, resulting in the creation of a new resource.
         */
        CREATED =                               201,
        /**
         * The request has been accepted for processing, but the processing has not been completed.
         * The request might or might not be eventually acted upon, and may be disallowed when processing occurs.
         */
        ACCEPTED =                              202,
        NON_AUTHORITATIVE_INFORMATION =         203,
        NO_CONTENT =                            204,
        RESET_CONTENT =                         205,
        PARTIAL_CONTENT =                       206,
        MULTI_STATUS =                          207,    // RFC4918
        ALREADY_REPORTED =                      208,    // RFC5842
        IM_USED =                               226,    // RFC3229
        MULTIPLE_CHOICES =                      300,
        /**
         * This and all future requests should be directed to the given URI.
         */
        MOVED_PERMANENTLY =                     301,
        /**
         * Tells the client to look at (browse to) another URL.
         */
        FOUND =                                 302,
        SEE_OTHER =                             303,
        NOT_MODIFIED =                          304,
        USE_PROXY =                             305,
        RESERVED =                              306,
        TEMPORARY_REDIRECT =                    307,
        PERMANENTLY_REDIRECT =                  308,    // RFC7238
        /**
         * This class of status code is intended for situations in which the error seems to have been caused by the client.
         * Except when responding to a HEAD request, the server should include an entity containing an explanation of the
         * error situation, and whether it is a temporary or permanent condition. These status codes are applicable to any
         * request method. User agents should display any included entity to the user.
         */
        BAD_REQUEST =                           400,
        /**
         * Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided.
         * The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource.
         * 401 semantically means "unauthenticated", i.e. the user does not have the necessary credentials.
         */
        UNAUTHORIZED =                          401,
        /**
         * Reserved for future use.
         *      The original intention was that this code might be used as part of some form of digital cash or micropayment scheme,
         *      as proposed for example by GNU Taler, but that has not yet happened, and this code is not usually used.
         *      Google Developers API uses this status if a particular developer has exceeded the daily limit on requests.
         *      Sipgate uses this code if an account does not have sufficient funds to start a call. Shopify uses this code
         *      when the store has not paid their fees and is temporarily disabled.
         */
        PAYMENT_REQUIRED =                      402,
        /**
         * The request was valid, but the server is refusing action. The user might not have the necessary permissions
         * for a resource, or may need an account of some sort.
         */
        FORBIDDEN =                             403,
        /**
         * The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible.
         */
        NOT_FOUND =                             404,
        /**
         * A request method is not supported for the requested resource; for example, a GET request on a form that requires
         * data to be presented via POST, or a PUT request on a read-only resource.
         */
        METHOD_NOT_ALLOWED =                    405,
        NOT_ACCEPTABLE =                        406,
        PROXY_AUTHENTICATION_REQUIRED =         407,
        REQUEST_TIMEOUT =                       408,
        CONFLICT =                              409,
        GONE =                                  410,
        LENGTH_REQUIRED =                       411,
        PRECONDITION_FAILED =                   412,
        REQUEST_ENTITY_TOO_LARGE =              413,
        REQUEST_URI_TOO_LONG =                  414,
        UNSUPPORTED_MEDIA_TYPE =                415,
        REQUESTED_RANGE_NOT_SATISFIABLE =       416,
        EXPECTATION_FAILED =                    417,
        /**
         * This code was defined in 1998 as one of the traditional IETF April Fools' jokes, in RFC 2324, Hyper Text Coffee
         * Pot Control Protocol, and is not expected to be implemented by actual HTTP servers. The RFC specifies this code
         * should be returned by teapots requested to brew coffee. This HTTP status is used as an Easter egg in some
         * websites, including Google.com.
         */
        I_AM_A_TEAPOT =                         418,    // RFC2324
        MISDIRECTED_REQUEST =                   421,    // RFC7540
        UNPROCESSABLE_ENTITY =                  422,    // RFC4918
        LOCKED =                                423,    // RFC4918
        FAILED_DEPENDENCY =                     424,    // RFC4918
        TOO_EARLY =                             425,    // RFC-ietf-httpbis-replay-04
        UPGRADE_REQUIRED =                      426,    // RFC2817
        PRECONDITION_REQUIRED =                 428,    // RFC6585
        TOO_MANY_REQUESTS =                     429,    // RFC6585
        REQUEST_HEADER_FIELDS_TOO_LARGE =       431,    // RFC6585
        UNAVAILABLE_FOR_LEGAL_REASONS =         451,
        /**
         * A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
         */
        INTERNAL_SERVER_ERROR =                 500,
        NOT_IMPLEMENTED =                       501,
        BAD_GATEWAY =                           502,
        SERVICE_UNAVAILABLE =                   503,
        GATEWAY_TIMEOUT =                       504,
        VERSION_NOT_SUPPORTED =                 505,
        VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL =  506,    // RFC2295
        INSUFFICIENT_STORAGE =                  507,    // RFC4918
        LOOP_DETECTED =                         508,    // RFC5842
        NOT_EXTENDED =                          510,    // RFC2774
        NETWORK_AUTHENTICATION_REQUIRED =       511;    // RFC6585

    /**
     * Status codes translation table.
     * @var array
     */
    private static $statusTexts = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Too Early',                                                   // RFC-ietf-httpbis-replay-04
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];

    /**
     * Returns the text of the given status code
     * @param int $code
     * @return string
     */
    public static function toText(int $code): ?string {
        return self::$statusTexts[$code] ?? null;
    }

    /**
     * Returns true if a given status code exists
     * @return bool
     */
    public static function exists(int $code): bool {
        return (true === isset($code, self::$statusTexts));
    }
}