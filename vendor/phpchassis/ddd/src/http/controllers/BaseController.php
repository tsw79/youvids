<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/25/2019
 * Time: 01:03
 */
namespace phpchassis\http\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\auth\Csrf;
use phpchassis\http\FormRequest;
use phpchassis\http\middleware\ {Constants, ServerRequest, Uri};
use phpchassis\data\DIContainer;

// @TODO Need to remove these!
use phpchassis\data\db\FluentPdoAdapter;
use phpchassis\data\db\MysqlPdoDatabaseConnection;
use phpchassis\data\db\base\DatabaseAdapterInterface;

/**
 * Class BaseController
 *
 * @package phpchassis\data\db\base
 */
abstract class BaseController {

    /**
     * @var AppRequest|FormRequest
     */
    protected $request;

    /**
     * @var string  FQN of FormRequest
     */
    protected $formRequest;

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [];

    /**
     * Name of the Controller's action being requested
     * @var string
     */
    protected $action;

    /**
     * @var object
     */
    protected $params;

    /**
     * @var \phpchassis\data\db\base\DatabaseAdapterInterface
     */
    protected $dbAdapter;

    /**
     * BaseController constructor.
     */
    public function __construct() {
        $this->request = new ServerRequest();
        $this->request->initialize();

        // Register all dependancies with the DIContainer
        $this->register();
    }

    /**
     * Register dependencies
     */
    protected function register() {

        /* TODO FOR TESTING PURPOSES: Should I leave this as is or let every controller handle its own db adapter? */
        //$this->dbAdapter = (new DatabaseAdapterFactory())->create();
        $this->dbAdapter = new FluentPdoAdapter(
            new MysqlPdoDatabaseConnection()
        );
        DIContainer::instance()->register(DatabaseAdapterInterface::class, $this->dbAdapter);
        // ------------------------------------------------------------------------------------------------------------

        if (!empty($this->dependencies)) {
            foreach ($this->dependencies as $dependency) {
                DIContainer::instance()->register($dependency);
            }
        }
    }

    /**
     * Wrapper for PHP core's functionality for redirecting web pages
     * @param $url
     * @param bool $permanent
     */
    protected function redirect($url, $permanent = false) {

        if(false === headers_sent()) {
            header("Location: " . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }

    /**
     * Checks if the action needs and Auth user and that the User is logged in
     * @redirect home page
     */
    protected function runAuthentication() {

        if (!AuthUser::isLoggedIn()) {

            // Log this action and include IP address and so on...

            $this->redirect(APP_ROOT . "/src/application/views/user/signIn.php");
        }
    }

    /**
     * Returns a response object to the View
     * @param array|null $data
     * @return Response
     */
    protected function response(array $params = []): Response {

        $dat = ('' === $this->request->getParsedBody()) ? array() : $this->request->getParsedBody();

        // We build a new request object so as to hide some of the API of the FormRequest / ServerRequest classes.
        $responseRequest = (object) [
            'results' => $this->request instanceof FormRequest ? $this->request->results() : array(),
            'phpself' => $this->request->getServerParam('PHP_SELF'),
            'token'   => Csrf::token()
        ];

        return Response::create($responseRequest, $dat, $params);
    }
}