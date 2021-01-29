<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 02:12
 */
namespace youvids\data;

use phpchassis\lib\html\HtmlElementProvider;
use youvids\domain\entities\ {User, Video};
use youvids\domain\repositories\ {UserRepo, VideoRepo};

/**
 * Class VideoInfoControls
 * @package youvids\data
 */
class VideoInfoControls implements VideoControlsInterface {

    use VideoControls;

    /**
     * @var Video
     */
    protected $video;

    /**
     * @var UserRepo
     */
    private $videoRepo;

    /**
     * @var UserRepo
     */
    private $userRepo;

    /**
     * @var User
     */
    private $uploadedBy;

    /**
     * VideoInfoControls constructor.
     * @param UserRepo $userRepo
     * @param VideoRepo $videoRepo
     * @param Video $video
     * @param User $loggedInUser
     */
    public function __construct(User $loggedInUser, Video $video, UserRepo $userRepo, VideoRepo $videoRepo, User $uploadedBy) {

        $this->authUser = $loggedInUser;
        $this->video = $video;
        $this->userRepo = $userRepo;
        $this->videoRepo = $videoRepo;
        $this->uploadedBy = $uploadedBy;
    }

    /**
     * Returns the Video Info's controls
     * @return array
     */
    public function create(): array {

        return [
            "primaryControls"   =>  [
                    "likeButton"    =>  $this->likeButton(),
                    "dislikeButton" =>  $this->dislikeButton()
            ],
            "secondaryControls" =>  [
                    "profileButton" =>  $this->userProfileButton($this->uploadedBy->username(), $this->uploadedBy->profilePic()),
                    "actionButton"  =>  $this->authUser->hasUploadedVideo($this->video->uploadedById())
                        ? $this->editVideoButton()
                        : $this->subscribeButton()
            ]
        ];
    }

    /**
     * Returns the Like button in html
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    private function likeButton(): string {

        $likes = $this->videoRepo->numLikes($this->video->id());
        $action = "likeVideo(this, {$this->video->id()})";
        $class = "likeButton";
                                                                                                    
        $imageSrc = $this->videoRepo->wasLikedBy($this->video->id(), $this->authUser->id())
            ? WEB_ROOT .  "/images/icons/thumb-up-active.png"
            : WEB_ROOT .  "/images/icons/thumb-up.png";

        return HtmlElementProvider::button($likes, $imageSrc, $action, $class);
    }

    private function dislikeButton(): string {

        $dislikes = $this->videoRepo->numDislikes($this->video->id());
        $action = "dislikeVideo(this, {$this->video->id()})";
        $class = "dislikeButton";

        $imageSrc = $this->videoRepo->wasDislikedBy($this->video->id(), $this->authUser->id())
            ? WEB_ROOT .  "/images/icons/thumb-down-active.png"
            : WEB_ROOT .  "/images/icons/thumb-down.png";

        return HtmlElementProvider::button($dislikes, $imageSrc, $action, $class);
    }

    /**
     * Returns the Edit Video button in html
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    private function editVideoButton(): string {

        $url = "/src/application/views/video/edit.php?videoId={$this->video->id()}";

        // @TODO Use the HetmlElement class to generate the anchor tag
        return "<div class='editVideoButtonContainer'>
                    <a href='{$url}'>
                        <button class='edit button'>
                            <span class='text'>EDIT VIDEO</span>
                        </button>
                    </a>
                </div>";
    }

    /**
     * Returns the Subscriber button in html
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    private function subscribeButton() {

        $isSubscribed = $this->userRepo->isSubscribed(
            $this->uploadedBy->id(),
            $this->authUser->id()
        );

        $buttonText = $isSubscribed ? "SUBSCRIBED" : "SUBSCRIBE";
        $buttonText .= " " . $this->userRepo->countSubscribers($this->uploadedBy->id()) ;

        $buttonClass = $isSubscribed ? "unsubscribe button" : "subscribe button";
        $action = "subscribe(\"{$this->uploadedBy->username()}\", \"{$this->authUser->username()}\", this)";

        return "<div class='subscribeButtonContainer'>
                    <button class='$buttonClass' onclick='$action'>
                        <span class='text'>$buttonText</span>
                    </button>
                </div>";
    }
}