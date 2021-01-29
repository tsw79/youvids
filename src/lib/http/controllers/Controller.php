<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/23/2019
 * Time: 23:46
 */
namespace youvids\lib\http\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\data\DIContainer;
use phpchassis\data\dto\AuthorizeCredential;
use phpchassis\http\controllers\BaseController;
use phpchassis\http\middleware\ServerRequest;
use phpchassis\lib\exceptions\AuthenticationException;
use youvids\domain\repositories\UserRepo;
use youvids\lib\http\middleware\file\UploadedVideo;

/**
 * Class Controller
 * @package youvids\lib\http\controllers
 */
class Controller extends BaseController {

  /**
   * Controller constructor.
   * @override
   */
  public function __construct() {

    if (null != $this->formRequest) {
      $this->request = new $this->formRequest();

      // Check for any files uploaded
      if (UploadedVideo::hasAny()) {
        $this->request->withUploadedFiles(
            //$this->createUploadedVideos()
            UploadedVideo::createByRequest($this->request)
        );
      }
    }
    else {
      $this->request = new ServerRequest();
    }

    $this->request->initialize();
    $requestUri = $this->request->getServerParam('REQUEST_URI_FULL');
    $this->request->withRequestTarget($requestUri);

    // Register all dependencies with the DIContainer
    $this->register();

    // TODO Need to find a different approach to authorization!!
    // Check to see if the current user has access to the requested controller's page (action)
    //$this->authorize();
  }

  // ------------------------- THIS METHOD IS POSTPONED UNTIL FURTHER NOTICE -----------------------------------------
  /**
   * Authorizes a request
   *
   * @note    $childController = (new \ReflectionClass($this))->getShortName();   // Without the Fully Qualified Name (FQN), just the class name!
   * @note    $childController = get_class($this);                                // Since php 5.0 - returns FQN
   * @note    $childController = get_called_class();                              // Since php 5.3 - returns FQN
   *
   * @return bool
   * @throws \ReflectionException
   * @throws \phpchassis\lib\exceptions\AuthenticationException
   * @throws \phpchassis\lib\exceptions\DIContainerException
   */
  protected function authorize() {

    // @TODO Not sure if this is the right place to do this!!
    $userRepo = DIContainer::instance()->get(UserRepo::class);
    $authUser = AuthUser::instance()->loggedInEntity($userRepo);
    $credential = AuthorizeCredential::create($authUser->accessStatus(), $authUser->accessLevel());
    // Controller that initiated the request
    $controller = (new \ReflectionClass($this))->getShortName();
    $hasAccess = $this->request->authorize($credential, $controller);

    if (!$hasAccess) {
      throw new AuthenticationException("You are not authorised!");
    }
  }
}