<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/12/2019
 * Time: 00:45
 */
namespace phpchassis\auth;

use phpchassis\storage\session\Session;

/**
 * Class Authenticator
 * @package phpchassis-ddd\auth
 */
class Csrf {

    /**
     * ERROR_AUTH
     */
    const ERROR_AUTH = 'ERROR: !nvalid token!';

    /**
     * Returns a CSRF token
     * @return string
     * @throws \Exception
     */
    public static function token(): string {

        return (self::exists() && !self::expired())
            ? Session::instance()->get("token")     // Token exists AND it hasn't expired
            : self::genToken();                     // Generates a new token
    }

    /**
     * Generates a security token, which helps prevent Cross Site Request Forgery (CSRF) attacks.
     * @return string
     * @throws \Exception
     */
    public static function genToken(): string {

        // @TODO Need to integrate this with the framework
        //$token = bin2hex(random_bytes(16));
        $token = urlencode(base64_encode((random_bytes(32))));

        Session::instance()->set("token", $token);
        return $token;
    }

    /**
     * Checks to see if two tokens are the same
     * @param $token
     * @return bool
     */
    public static function matchToken($token) {

        $sessToken = Session::instance()->get("token") ?? date('Ymd');
        return ($token == $sessToken);
    }

    /**
     * Returns true if the session 'token' exists
     * @return bool
     */
    public static function exists(): bool {
        return (true == Session::instance()->get("token"));
    }

    /**
     * Returns true if the token has expired
     * @return bool
     */
    public static function expired(): bool {

        // @TODO Business Logic:
        //      Token lasts for x-amount of time. Just before that time has elapsed, we need to renew the token. Else
        //      we use the same token.

        return false;
    }
}