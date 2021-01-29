<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/5/2019
 * Time: 07:18
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\http\controllers\BaseController;
use phpchassis\data\DIContainer;
use youvids\data\VideoGrid;
use youvids\domain\repositories\ {SubscriberRepo, ThumbnailRepo, UserRepo, VideoRepo};

/**
 * Class SiteController
 * @package youvids\application\controllers
 */
class SiteController extends BaseController {

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
     * @action home
     * @return array
     * @throws \ReflectionException
     * @throws \phpchassis\exceptions\AuthenticationException
     * @throws \phpchassis\exceptions\DIContainerException
     */
    public function home() {

        if (AuthUser::isLoggedIn()) {

            $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);
            $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);
            $videoRepo = DIContainer::instance()->get(VideoRepo::class);

            $userRepo = (DIContainer::instance()->get(UserRepo::class))
                ->withAssocRepo("subscriber", $subscriberRepo)
                ->toObject();

            $authUser = AuthUser::instance()->loggedInEntity($userRepo);
            $subscriptions = $userRepo->findSubscriptions($authUser->id());
            $videos = array();

            if (sizeof($subscriptions) > 0) {
                $videos = $videoRepo->findAllBySubscriptionUsers($subscriptions);
                $title = "Subscriptions";
            }
            else {
                $videos = $videoRepo->findAllRandomly();
                $title = "Recommended";
            }

            $videoRepo
                ->withAssocRepo("thumbnail", $thumbnailRepo)
                ->toObject();

            $videoGrid = (new VideoGrid(
                $videos,
                $title
            ))->create();

            return [
                "videoGrid" => $videoGrid
            ];
        }
    }

    public function about() { }

    public function contactUs() { }
}