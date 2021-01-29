<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/12/2019
 * Time: 01:07
 */
namespace phpchassis\auth\adapters;

use phpchassis\auth\AuthenticateInterface;
use Psr\Http\Message\ {RequestInterface, ResponseInterface};

/**
 * Class FileAuthAdapter
 * @package phpchassis-ddd\auth
 */
class FileAuthAdapter implements AuthenticateInterface {

    public function authenticate(RequestInterface $request) : ResponseInterface {
        // TODO: Implement authenticate() method.
    }
}