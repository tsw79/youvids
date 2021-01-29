<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/12/2019
 * Time: 03:46
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\http\controllers\BaseController;
use phpchassis\lib\collections\Collection;
use youvids\data\CommentItem;
use youvids\domain\repositories\ {CommentDislikeRepo, CommentLikeRepo, CommentRepo, UserRepo};

/**
 * Class CommentController
 * @package youvids\application\controllers
 */
class CommentController extends BaseController {

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [
        UserRepo::class,
        CommentRepo::class,
        CommentLikeRepo::class,
        CommentDislikeRepo::class
    ];

    /**
     * Returns all replies for a given comment
     * @ajax
     * @param int $commentId
     * @param int $videoId
     * @return string
     * @throws \phpchassis\exceptions\AuthenticationException
     */
    public function replies(): string {

        if ($this->request->isGetMethod()) {
            return false;
        }

        if (!$this->params->commentId || !$this->params->videoId) {
            return "One or more parameters are not passed into the getCommentReplies.php file";
        }

        // @TODO Validate input data

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $commentRepo = DIContainer::instance()->get(CommentRepo::class);

        $replies = $commentRepo->allReplies($this->params->commentId);

        $replyItems = (new CommentItem(
            AuthUser::instance()->loggedInEntity($userRepo),
            $commentRepo,
            $userRepo,
            $replies
        ))->create();

        return $replyItems;
    }

    /**
     * Adds a new comment and returns the new CommentItem in html
     * @ajax
     * @param string $commentText
     * @param int $postedBy
     * @param int $videoId
     * @param int $responseTo
     * @return string
     * @throws \HttpRequestException
     * @throws \phpchassis\exceptions\AuthenticationException
     */
    public function add(): string {

        if (!$this->request->isPostMethod()) {
            throw new \HttpRequestException("Invalid request!");
        }

        if (!$this->params->commentText || !$this->params->videoId) {
            return "One or more parameters are not passed into the postComment.php file";
        }

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $commentRepo = DIContainer::instance()->get(CommentRepo::class);
        $responseTo = $this->params->responseTo ? $this->params->responseTo : null;

        $newId = $commentRepo->addOne(
            $this->params->videoId,
            $this->params->commentText,
            $responseTo
        );

        if ($newId) {

            $postedBy = AuthUser::instance()->loggedInEntity($userRepo);
            $comment = $commentRepo->byId($newId);

            // Need to pass a Collection to CommentItem!
            $collection = new Collection();
            $collection->set($comment);

            $item = (new CommentItem(
                $postedBy,
                $commentRepo,
                $userRepo,
                $collection
            ))->create();

            return $item;
        }
        else {
            return "Error inserting comment.";
        }
    }

    /**
     * like (Comment) action
     * @ajax
     * @param int $commentId
     * @return bool|string
     * @throws \phpchassis\exceptions\AuthenticationException
     */
    public function like() {

        if ($this->request->isGetMethod()) {
            return false;
        }

        if (!$this->params->commentId) {
            return "One or more parameters are not passed into the likeComment.php file";
        }

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $commentLikeRepo = DIContainer::instance()->get(CommentLikeRepo::class);
        $commentDislikeRepo = DIContainer::instance()->get(CommentDislikeRepo::class);

        $commentRepo = (DIContainer::instance()->get(CommentRepo::class))
            ->withAssocRepo("like", $commentLikeRepo)
            ->withAssocRepo("dislike", $commentDislikeRepo)
            ->toObject();

        $loggedInUser = AuthUser::instance()->loggedInEntity($userRepo);
        return $commentRepo->like($this->params->commentId, $loggedInUser->id());
    }

    /**
     * dislike (Comment) action
     * @ajax
     * @return bool|string
     * @throws \phpchassis\exceptions\AuthenticationException
     */
    public function dislike() {

        if ($this->request->isGetMethod()) {
            return false;
        }
        if (!$this->params->commentId) {
            return "One or more parameters are not passed into the dislikeComment.php file";
        }

        $userRepo = DIContainer::instance()->get(UserRepo::class);
        $commentLikeRepo = DIContainer::instance()->get(CommentLikeRepo::class);
        $commentDislikeRepo = DIContainer::instance()->get(CommentDislikeRepo::class);

        $commentRepo = (DIContainer::instance()->get(CommentRepo::class))
            ->withAssocRepo("like", $commentLikeRepo)
            ->withAssocRepo("dislike", $commentDislikeRepo)
            ->toObject();

        $loggedInUser = AuthUser::instance()->loggedInEntity($userRepo);
        return $commentRepo->dislike($this->params->commentId, $loggedInUser->id());
    }
}