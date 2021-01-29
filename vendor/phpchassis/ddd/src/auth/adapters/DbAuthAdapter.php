<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/11/2019
 * Time: 21:37
 */
namespace phpchassis\auth\adapters;

use phpchassis\auth\AuthenticateInterface;
use phpchassis\cryptography\PasswordWrapper;
use phpchassis\data\dto\PersistentDataInterface;
use phpchassis\http\middleware\ {Response, TextStream};
use Psr\Http\Message\ {RequestInterface, ResponseInterface};

/**
 * Class DbAuthAdapter
 * @package phpchassis-ddd\auth
 */
class DbAuthAdapter implements AuthenticateInterface {

    /**
     * @var PersistentDataInterface
     */
    private $credentials;

    /**
     * ERROR_AUTH
     */
    const ERROR_AUTH = 'ERROR: authentication error';

    /**
     * DbAuthAdapter constructor.
     *
     * @param PersistentDataInterface $credentials
     */
    public function __construct(PersistentDataInterface $credentials) {
        $this->credentials = $credentials;
    }

    /**
     * The core authenticate() method extracts the username and password from the request object. We then do a straightforward
     * database lookup. If there is a match, we store user information in the response body, JSON-encoded.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \PhpChassis\middleware\InvalidArgumentException
     */
    public function authenticate(RequestInterface $request) : ResponseInterface {

        $code = 401;
        //$info = false;
        $body = new TextStream(self::ERROR_AUTH);
        $params = json_decode($request->getBody()->getContents());
        $response = new Response();

        if(PasswordWrapper::instance()->match($params->password, $this->credentials->password)) {

            unset($this->credentials->password);
            $body = new TextStream(json_encode($this->credentials));
            $response->withBody($body);
            $code = 202;
        }

        return $response->withBody($body)->withStatus($code);
    }
}