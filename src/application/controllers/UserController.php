<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/22/2019
 * Time: 02:27
 */
namespace youvids\application\controllers;

use phpchassis\auth\ {AccessControl, AuthUser};
use phpchassis\http\controllers\Response;
use phpchassis\http\FlashMessage;
use phpchassis\data\DIContainer;
use phpchassis\storage\session\Session;
use phpchassis\lib\exceptions\AuthenticationException;
use phpchassis\lib\traits\PhpCommons;
use youvids\application\services\GenUserProfileService;
use youvids\application\dto\ {SignInUserRequest, UserProfileRequest};
use youvids\data\VideoGrid;
use youvids\domain\entities\User;
use youvids\domain\repositories\ {SubscriberRepo, ThumbnailRepo, UserRepo, VideoRepo};
use youvids\application\services\AuthenticateUserService;
use youvids\lib\http\controllers\Controller;

/**
 * Class UserController
 * @package YouVids\controllers
 */
class UserController extends Controller {

    use PhpCommons;

    /**
     * @override
     * @var string      Name of the FormRequest class
     */
    protected $formRequest = UserRequest::class;

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [
        UserRepo::class,
        VideoRepo::class,
        ThumbnailRepo::class,
        SubscriberRepo::class
    ];

    /**
     * signInAction
     * @return Response
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function signIn(): Response {

        if ($this->request->isPostMethod() && $this->request->isValid()) {

            try {
                $userRepo = DIContainer::instance()->get(UserRepo::class);
                $authUserService = new AuthenticateUserService($userRepo);

                // @TODO Ideally, I'd like the code to read like in the following link:
                // @ref: https://stackoverflow.com/questions/47656592/laravel-5-5-login-explained
                //
                //  if ($this->hasTooManyLoginAttempts($request)) {
                //      $this->fireLockoutEvent($request);
                //      return $this->sendLockoutResponse($request);
                //  }
                //
                //  if ($this->attemptLogin($request)) {
                //      return $this->sendLoginResponse($request);
                //  }
                //
                //  $this->incrementLoginAttempts($request);
                //  return $this->sendFailedLoginResponse($request);
                //

                // @TODO Need to implement the App Service's Response object
                $response = $authUserService->execute(new SignInUserRequest(
                    $this->request->username,
                    $this->request->password,
                    $this->request->token
                ));

                if ($response->isSuccessfull()) {
                    $this->redirect("/index.php");
                }
                else {
                    FlashMessage::instance()->error($response->getReasonPhrase());
                }
            }
            catch (AuthenticationException $e) {
                echo $e->getMessage();
            }
        }

        return $this->response();
    }

    /**
     * signUpAction
     * @throws \Exception
     */
    public function signUp(): Response {

        if ($this->request->isPostMethod() && $this->request->isValid()) {

            $userRepo = DIContainer::instance()->get(UserRepo::class);

            $user = (new User())
                ->firstName($this->request->firstName)
                ->lastName($this->request->lastName)
                ->username($this->request->username)
                ->email($this->request->email)
                ->password($this->request->password);

            $userId = $userRepo->newRecord($user);

            if (!$userId || null == $userId) {
                echo "User registration details failed to insert!";
                exit;
            }

            // @TODO Need to add the logged-in user to session in the same way the auth service does
            Session::instance()->set(
                AccessControl::SESSION_AUTH_KEY,
                ((object) ["username" => $user->username()])
            );

            $this->redirect(APP_ROOT . "/index.php");
        }

        return $this->response();
    }

    /**
     * (User) profileAction
     * @return mixed
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function profile() {

        $this->runAuthentication();

        if ($this->request->isGetMethod()) {

            $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);
            $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

            $userRepo = (DIContainer::instance()->get(UserRepo::class))
                ->withAssocRepo("subscriber", $subscriberRepo)
                ->toObject();

            $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
                ->withAssocRepo("thumbnail", $thumbnailRepo)
                ->toObject();

            $userProfileService = (new GenUserProfileService($userRepo))
                ->withParams(["videoRepo" => $videoRepo])
                ->toObject();

            return $userProfileService->execute(new UserProfileRequest(
                $this->request->username
            ));
        }
        else {
            throw new \BadMethodCallException("Profile not found");
        }
    }

    /**
     * @action signOut
     * @redirect home page
     */
    public function signOut() {

        if (AuthUser::isLoggedIn()) {
            Session::instance()->close();
        }
        $this->redirect("/index.php");
    }

    /**
     * @action subscriptions
     * @return string
     * @throws AuthenticationException
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function subscriptions(): string {

        $this->runAuthentication();

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);
        $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

        $videoRepo = DIContainer::instance()->get(VideoRepo::class)
            ->withAssocRepo("thumbnail", $thumbnailRepo)
            ->toObject();

        $authUser = AuthUser::instance()->loggedInEntity($userRepo);
        $subscriptionIds = $subscriberRepo->findAllSubscriptionIds($authUser->id());
        $videos = null;

        if ($subscriptionIds) {
            $videos = $videoRepo->findAllBySubscriptionIds($subscriptionIds);
        }

        if ($this->isCollection($videos) && sizeof($videos) > 0) {

            $gridHtml = (new VideoGrid(
                $videos,
                "New from your subscriptions",
                true,
                true
            ))->create();
        }
        else {
            $gridHtml = "No videos to show here.";
        }

        return $gridHtml;
    }

    /**
     * Subscribes a user to another user
     * @action subscribe
     * @return bool|string
     * @throws \ReflectionException
     * @throws \phpchassis\exceptions\DIContainerException
     */
    public function subscribe() {

        if ($this->request->isGetMethod()) {
            return false;
        }

        if (!$this->request->userTo || !$this->request->userFrom) {
            return "One or more parameters are not passed into the subscribe.php file";
        }

        // @TODO Validate input data

        $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);
        $userRepo = (DIContainer::instance()->get(UserRepo::class))
            ->withAssocRepo("subscriber", $subscriberRepo)
            ->toObject();

        $userTo = $userRepo->findByUsername($this->request->userTo);
        $userFrom = $userRepo->findByUsername($this->request->userFrom);
        $isSubscribed = $userRepo->isSubscribed($userTo->id(), $userFrom->id());

        if ($isSubscribed) {
            $userRepo->unsubscribe($userTo->id(), $userFrom->id());
        }
        else {
            $userRepo->subscribe($userTo->id(), $userFrom->id());
        }

        return $subscriberRepo->countBySubscribedTo($userTo->id());
    }
}