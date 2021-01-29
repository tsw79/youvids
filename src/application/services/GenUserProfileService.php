<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/2/2019
 * Time: 08:09
 */
namespace youvids\application\services;

use phpchassis\auth\AuthUser;
use phpchassis\data\dto\RequestDataInterface;
use phpchassis\data\service\ {ApplicationService, ApplicationServiceInterface};
use youvids\data\VideoGrid;

/**
 * Class UserProfileService
 *  Generates the profile for a particular User
 *
 * @package youvids\application\services
 */
class GenUserProfileService extends ApplicationService implements ApplicationServiceInterface {

    /**
     * Executes the service
     * @param RequestDataInterface $requestData
     * @return array|mixed
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function execute(RequestDataInterface $requestData) {

        $subscriberButton = '';

        // If the username passed is equal to the logged-in users', then  show the logged-in user's profile, else someone else's
        if (AuthUser::sessionData()->username == $requestData->username) {
            $profiler = AuthUser::instance()->loggedInEntity($this->repository);
        }
        else {
            $profiler = $this->repository->byUsername($requestData->username);

            // @TODO Need to use the method 'subscribeButton' in class 'VideoInfoControls' to generate this button. I should probably move it out into a different class!
            $subscriberButton = "TODO: SUBSCRIBER BUTTON";
        }

        $videos = $this->videoRepo->findAllByUploadedUser($profiler->id());

        if ($videos) {
            $videoGridHtml = (new VideoGrid($videos))->create();
        }
        else {
            $videoGridHtml = "<span>This user has no videos</span>";
        }

        $subscriberCount = $this->repository->countSubscribers($profiler->id());

        //@TODO Need to create and return a UserProfileResponse object
        return [
            "photoContainer" => [
                "src"      => $profiler->coverPhoto(),
                "channel"  => $profiler->fullName()
            ],
            "header"         => [
                "src"      => $profiler->profilePic(),
                "fullName" => $profiler->fullName(),
                "count"    => $subscriberCount,
                "button"  => $subscriberButton
            ],
            "content"        => [
                "videoGrid"=> $videoGridHtml,
                "about"    => [
                    "name"          => $profiler->fullName(),
                    "username"      => $profiler->username(),
                    "numSubscribers"=> $subscriberCount,
                    "totalViews"    => $this->videoRepo->findSumViewsByUploadedUser($profiler->id()),
                    "signup"        => $profiler->displaySignup("F jS, Y")
                ]
            ]
        ];
    }
}