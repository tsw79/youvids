<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/11/2019
 * Time: 21:33
 */
namespace phpchassis\auth\adapters;

use phpchassis\auth\AuthenticateInterface;
use Psr\Http\Message\ {RequestInterface, ResponseInterface};

/**
 * Class OAuth2Adapter
 *
 * @package phpchassis-ddd\auth
 */
class OAuth2Adapter implements AuthenticateInterface {

    public function authenticate(RequestInterface $request) : ResponseInterface {
      // TODO: Implement authenticate() method.
    }
}