<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/12/2019
 * Time: 01:20
 */
namespace phpchassis\auth;

use phpchassis\data\service\application\AuthorizeRequestService;
use phpchassis\http\middleware\ {HttpStatusCode, Response, TextStream};
use Psr\Http\Message\RequestInterface;

/**
 * Class AccessControl
 * @package PhpChassis\auth
 */
class AccessControl {

    /**
     * SESSION_KEY for logged-in users
     */
    public const SESSION_AUTH_KEY = "auth";

    /**
     * Processes a user's login authentication
     *
     * @throws \PhpChassis\middleware\InvalidArgumentException
     */
//    public function procLogin() {
//
//        // Set up the authentication adapter and core class
//
//        // TODO Move this to a factory so it can communicate with the Config Loader and dynamically load the set adapter
//        $authAdapter = new \phpchassis\auth\DbAuthAdapter();
//
//        $auth = new Authenticate($authAdapter, self::SESSION_KEY);
//
//        // Be sure to initialize the incoming request, and set up the request to be made to the authentication class.
//        $incoming = new ServerRequest();
//        $incoming->initialize();
//        $outbound = new Request();
//
//        // Check the incoming class method to see if it is POST. If so, pass a request to the authentication class.
//        if ($incoming->getMethod() == Constants::METHOD_POST) {
//
//            $body = new TextStream(json_encode($incoming->getParsedBody()));
//            $response = $auth->procAuthentication($outbound->withBody($body));
//        }
//
//        $action = $incoming->getServerParams()['PHP_SELF'];
//
//        var_dump($action);
//    }
}