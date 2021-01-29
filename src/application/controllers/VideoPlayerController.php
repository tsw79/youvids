<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 06:07
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\http\controllers\BaseController;
use phpchassis\data\DIContainer;
use phpchassis\lib\traits\PhpCommons;
use youvids\domain\repositories\ {
    CommentDislikeRepo, CommentLikeRepo, CommentRepo, SubscriberRepo, ThumbnailRepo, UserRepo,
    VideoDislikeRepo, VideoLikeRepo, VideoRepo
};
use youvids\data\ {VideoCommentsSection, VideoGrid, VideoInfoControls, VideoPlayer};
use youvids\lib\exceptions\VideoPlayerException;

/**
 * Class VideoPlayerController
 * @package youvids\controllers
 */
class VideoPlayerController extends BaseController {

    use PhpCommons;

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [
        VideoLikeRepo::class,
        VideoDislikeRepo::class,
        VideoRepo::class,
        SubscriberRepo::class,
        UserRepo::class,
        CommentLikeRepo::class,
        CommentDislikeRepo::class,
        CommentRepo::class,
        ThumbnailRepo::class
    ];

    /**
     * @action watch
     * @return array
     * @throws \HttpRequestException
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function watch(): array {

        if ($this->request->isGetMethod()) {

            try {

                $videoLikeRepo = DIContainer::instance()->get(VideoLikeRepo::class);
                $videoDislikeRepo = DIContainer::instance()->get(VideoDislikeRepo::class);
                $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);
                $commentLikeRepo = DIContainer::instance()->get(CommentLikeRepo::class);
                $commentDislikeRepo = DIContainer::instance()->get(CommentDislikeRepo::class);
                $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

                $userRepo = (DIContainer::instance()->get(UserRepo::class))
                    ->withAssocRepo("subscriber", $subscriberRepo)
                    ->toObject();

                $commentRepo = (DIContainer::instance()->get(CommentRepo::class))
                    ->withAssocRepo("like", $commentLikeRepo)
                    ->withAssocRepo("dislike", $commentDislikeRepo)
                    ->toObject();

                $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
                    ->withAssocRepo("videoLike", $videoLikeRepo)
                    ->withAssocRepo("videoDislike", $videoDislikeRepo)
                    ->withAssocRepo("comment", $commentRepo)
                    ->withAssocRepo("thumbnail", $thumbnailRepo)
                    ->toObject();

                $authUser = AuthUser::instance()->loggedInEntity($userRepo);
                $video = $videoRepo->findById($this->request->id);
                $videoRepo->incrementViews($video->id());

                // Get the user who uploaded the particular video
                $uploadedBy = $userRepo->findById($video->uploadedById());

                // ---------- Video Info Controls -----------------
                $videoInfoControls = (new VideoInfoControls(
                    $authUser,
                    $video,
                    $userRepo,
                    $videoRepo,
                    $uploadedBy
                ))->create();

                // ---------- Video Comment Section -----------------
                $videoCommentsSection = (new VideoCommentsSection(
                    $authUser,
                    $video,
                    $commentRepo,
                    $userRepo
                ))->create();

                //$videoCommentControls = new VideoCommentControls($video, $this->commentRepo, $authUser);
                //$commentControls = $videoInfoControls->create();

                $grid = (new VideoGrid(
                    $videoRepo->findAllRandomly()
                ))->create();

                return [
                    (new VideoPlayer($video, VideoPlayer::AUTOPLAY))->create(),
                    $video,
                    $uploadedBy,
                    $videoInfoControls["primaryControls"],
                    $videoInfoControls["secondaryControls"],
                    $videoCommentsSection,
                    $grid
                ];
            }
            catch (VideoPlayerException $e) {
                echo $e->getMessage();
            }
        }
        else {
            throw new \HttpRequestException("Request not valid.");
        }
    }
}