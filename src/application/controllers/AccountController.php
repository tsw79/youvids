<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/2/2019
 * Time: 22:47
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\http\controllers\BaseController;
use phpchassis\lib\exceptions\AuthenticationException;
use phpchassis\data\DIContainer;
use phpchassis\lib\traits\PhpCommons;
use youvids\domain\repositories\UserRepo;
use phpchassis\http\FlashMessage;

/**
 * Class AccountController
 * @package youvids\application\controllers
 */
class AccountController extends BaseController {

    use PhpCommons;

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [UserRepo::class];

    /**
     * Settings action
     */
    public function settings(): array {

        if (!AuthUser::isLoggedIn()) {
            throw new AuthenticationException("User not logged in.");
        }

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $authUser = AuthUser::instance()->loggedInEntity($userRepo);

        if ($this->request->isPostMethod()) {

            // Check whether the User details form was sent
            if (isset($this->params->btnDetails)) {

                // @TODO Authenticate input data

                $emailVerified = $userRepo->verifyEmail(
                    $authUser->username(),
                    $this->params->email
                );

                if ($emailVerified) {

                    // @TODO Need to use the built-in edit method and not custom SQL
                    $userRepo->editUserDetails(
                        $authUser->id(),
                        $this->params->firstName,
                        $this->params->lastName,
                        $this->params->email
                    );
                    FlashMessage::instance()->success("Details updated successfully!");
                }
                else {
                    FlashMessage::instance()->error("Email not valid.");
                }
            }
            // Check whether the Password form was sent
            elseif (isset($this->params->btnPassword)) {

                // @TODO SANITIZE and Authenticate user input, i.e. all passwords!!!

                // @TODO Need to check whether the two passwords are equal or not

                $passwdVerified = $userRepo->verifyPassword(
                    $authUser->username(),
                    $this->params->oldPassword
                );

                if ($passwdVerified) {

                    $userRepo->editPassword(
                        $this->params->newPassword,
                        $authUser->id()
                    );
                    FlashMessage::instance()->success( "Password updated successfully!");
                }
                else {
                    FlashMessage::instance()->error("Password not valid.");
                }
            }
            else {
                FlashMessage::instance()->error("Oops! Something went wrong.");
            }
        }

        // @TODO Need to return a Response object
        return [
            $authUser->firstName(),
            $authUser->lastName(),
            $authUser->email(),
            FlashMessage::instance()->messages()
        ];
    }
}