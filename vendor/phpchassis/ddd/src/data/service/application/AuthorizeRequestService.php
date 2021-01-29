<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/4/2019
 * Time: 05:26
 */
namespace phpchassis\data\service\application;

use phpchassis\data\dto\RequestDataInterface;
use phpchassis\data\service\ {ApplicationServiceInterface, ApplicationService};
use phpchassis\http\middleware\HttpStatusCode;
use Psr\Http\Message\RequestInterface;

/**
 * Class AuthorizeRequestService
 * @package phpchassis\data\service\application
 */
class AuthorizeRequestService /*extends ApplicationService implements ApplicationServiceInterface*/ {

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var object
     */
    private $config;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var array
     */
    private $allowed;

    /**
     * AuthorizeRequestService constructor.
     * @param RequestInterface $request
     * @param object $config
     * @param string $controller
     * @param array $allowed
     */
    public function __construct(RequestInterface $request, object $config, string $controller, array &$allowed) {

        $this->request = $request;
        $this->config = $config;
        $this->controller = $controller;
        $this->allowed = $allowed;
    }

    /**
     * @param string $controller
     * @return mixed
     */
    //public function execute(RequestDataInterface $requestData) {
    public function execute() {
      // @TODO Implement execute() method
    }
}