<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/10/2019
 * Time: 02:00
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\http\controllers\Response;
use phpchassis\http\FlashMessage;
use phpchassis\data\DIContainer;
use phpchassis\lib\traits\PhpCommons;
use Symfony\Component\VarDumper\VarDumper;
use youvids\application\dto\UploadVideoRequest;
use youvids\application\services\UploadVideoService;
use youvids\data\ {VideoGrid, VideoPlayer, VideoThumbnailItems};
use youvids\domain\entities\Category;
use youvids\lib\exceptions\VideoUploadException;
use youvids\lib\http\controllers\Controller;
use youvids\domain\repositories\ {
    CategoryRepo, SubscriberRepo, ThumbnailRepo, UserRepo, VideoDislikeRepo, VideoLikeRepo, VideoRepo
};

/**
 * Class VideoController
 * @package youvids\application\controllers
 */
class VideoController extends Controller {

    use PhpCommons;

    /**
     * @override
     * @var string      Name of the FormRequest class
     */
    protected $formRequest = VideoRequest::class;

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [
        UserRepo::class,
        VideoRepo::class,
        CategoryRepo::class,
        ThumbnailRepo::class,
        SubscriberRepo::class,
        VideoLikeRepo::class
    ];

    /**
     * Uploads a new video
     * @action upload
     * @return Response
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function upload(): Response {

        // TODO Need to move it out of this class!!
        //$this->runAuthentication();

        $categoryRepo = DIContainer::instance()->get(CategoryRepo::class);

        if ($this->request->isPostMethod() && $this->request->isValid()) {

            try {
                $videoRepo = DIContainer::instance()->get(VideoRepo::class);
                $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

                $uploadService = (new UploadVideoService($videoRepo))
                    ->withParams(["thumbnailRepo" => $thumbnailRepo])
                    ->toObject();

                $uploadVideoRequestData = new UploadVideoRequest(
                    $this->request->title,
                    $this->request->description,
                    $this->request->privacy,
                    $this->request->category,
                    $this->request->videoFile
                );

                $successful = $uploadService->execute($uploadVideoRequestData);
                $this->redirect(APP_ROOT . "/index.php");
            }
            catch (VideoUploadException $e) {
                echo $e->getCode() . ": " . $e->getMessage();
                exit;
            }
        }

        $categories = $categoryRepo->findAll();
        return $this->response([
            "categoryOptions" => Category::categoryOptionsHtml($categories)
        ]);
    }

    /**
     * Edit video details and set selected thumbnails
     * @action edit
     * @return array
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function edit(): array {

        // Authenticate
        $this->runAuthentication();

        if (!isset($this->params->videoId)) {
            echo "No video selected.";
            exit(0);
        }

        // TODO Validate input data

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $videoRepo = DIContainer::instance()->get(VideoRepo::class);

        $authUser = AuthUser::instance()->loggedInEntity($userRepo);
        $video = $videoRepo->findById($this->params->videoId);

        if (!$video->wasUploadedBy($authUser->id())) {
            echo "Not your video";
            exit();
        }

        if ($this->request->isPostMethod() && isset($this->params->submit)) {

            // TODO Validate input data

            $video->title($this->params->title);
            $video->description($this->params->description);
            $video->privacy($this->params->privacy);
            $video->categoryId($this->params->category);

            $isSuccess = $videoRepo->editOne(
                $this->params->videoId,
                $video->title(),
                $video->description(),
                $video->privacy(),
                $video->categoryId()
            );

            if ($isSuccess) {
                FlashMessage::instance()->success("Details updated successfully!");
            }
            else {
                FlashMessage::instance()->error("An error occurred while updating your video.!");
            }
        }

        $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);
        $categoryRepo = DIContainer::instance()->get(CategoryRepo::class);

        $thumbItems = (new VideoThumbnailItems(
            $video->id(),
            $thumbnailRepo
        ))->create();

        // Need to return a Response object
        return [
            (new VideoPlayer($video))->create(),
            $thumbItems,
            $video,
            Category::categoryOptionsHtml(
                $categoryRepo->all(),
                $video->categoryId()
            ),
            FlashMessage::instance()->messages()
        ];
    }

    /**
     * Returns all videos that a given user has liked
     * @action liked (videos)
     * @return array
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function liked(): array {

        $this->runAuthentication();

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);
        $videoLikeRepo = DIContainer::instance()->get(VideoLikeRepo::class);

        $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
            ->withAssocRepo("videoLike", $videoLikeRepo)
            ->withAssocRepo("thumbnail", $thumbnailRepo)
            ->toObject();

        $authUser = AuthUser::instance()->loggedInEntity($userRepo);
        $videos = $videoRepo->findAllLikesByUser($authUser->id());

        if (null != $videos) {

            $videoGrid = (new VideoGrid(
                $videoRepo,
                $userRepo,
                $videos,
                "Videos that you have liked",
                false,
                true
            ))->create();
        }
        else {
            $videoGrid = "No liked videos to show.";
        }

        return ["grid" => $videoGrid];
    }

    /**
     * @action trending
     * @return array
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function trending(): array {

        $this->runAuthentication();

//        $userRepo = DIContainer::instance()->get(UserRepo::class);
//        $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);
        $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

        $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
            ->withAssocRepo("thumbnail", $thumbnailRepo)
            ->toObject();

        $videos = $videoRepo->findAllTrending();

        if ($this->isCollection($videos) && sizeof($videos) > 0) {

            $gridHtml = (new VideoGrid(
                $videos,
                "Trending videos uploaded in the last week",
                true,
                true
            ))->create();
        }
        else {
            $gridHtml = "No trending videos to show.";
        }

        return [
            "grid" => $gridHtml
        ];
    }

    /**
     * @action search
     * @return array|bool
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function search() {

        $videoGrid = "Not found!";

        if ($this->request->isGetMethod()) { 

            // if (!isset($this->request->term) || $this->request->term == '') {
            if ($this->request->term == '') {
                echo "You must enter a search term";
                return false;
            }

            $userRepo = DIContainer::instance()->get(UserRepo::class);
            $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

            $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
                ->withAssocRepo("thumbnail", $thumbnailRepo)
                ->toObject();
                
            $orderBy = (!isset($this->request->orderBy) || $this->request->orderBy == "views") ? "views" : "uploadDate";
            $videos = $videoRepo->search($this->request->term, $orderBy);

            $videoGrid = !is_null($videos)
                ? (new VideoGrid(
                        $videoRepo,
                        $userRepo,
                        $videos,
                        sizeof($videos) . " results found.",
                        true,
                        true
                  ))->create()
                : "No results found.";
        }

        return ["grid" => $videoGrid];
    }

    /**
     * @action like (video)
     * @return string
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function like(): string {

        if ($this->request->isGetMethod()) {
            return false;
        }

        // TODO Validate input data

        try {

            $userRepo = DIContainer::instance()->get(UserRepo::class);
            $videoLikeRepo = DIContainer::instance()->get(VideoLikeRepo::class);
            $videoDislikeRepo = DIContainer::instance()->get(VideoDislikeRepo::class);

            $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
                ->withAssocRepo("videoLike", $videoLikeRepo)
                ->withAssocRepo("videoDislike", $videoDislikeRepo)
                ->toObject();

            $authUser = AuthUser::instance()->loggedInEntity($userRepo);

            return $videoRepo->like(
                $this->params->videoId,
                $authUser->id()
            );
        }
        catch (VideoPlayerException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @action dislike
     * @return string
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function dislike(): string {

        if ($this->request->isGetMethod()) {
            return false;
        }

        // TODO Validate input data

        try {

            $userRepo = DIContainer::instance()->get(UserRepo::class);
            $videoLikeRepo = DIContainer::instance()->get(VideoLikeRepo::class);
            $videoDislikeRepo = DIContainer::instance()->get(VideoDislikeRepo::class);

            $videoRepo = (DIContainer::instance()->get(VideoRepo::class))
                ->withAssocRepo("videoLike", $videoLikeRepo)
                ->withAssocRepo("videoDislike", $videoDislikeRepo)
                ->toObject();

            $authUser = AuthUser::instance()->loggedInEntity($userRepo);

            return $videoRepo->dislike(
                $this->params->videoId,
                $authUser->id()
            );
        }
        catch (VideoPlayerException $e) {
            echo $e->getMessage();
        }
    }
}