<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/11/2019
 * Time: 19:48
 */
namespace phpchassis\auth;

use Psr\Http\Message\ {RequestInterface, ResponseInterface};

/**
 * Interface AuthenticateInterface
 *  We use this interface to support the Adapter software design pattern, making our Authenticate class more generically
 *  useful by allowing a variety of adapters, each of which can draw authentication from a different source
 *  (for example, from a file, using OAuth2, and so on). Note the use of the PHP 7 ability to define the return
 *  value data type.
 *
 * @package phpchassis-ddd\auth
 */
interface AuthenticateInterface {

    public function authenticate(RequestInterface $request) : ResponseInterface;
}