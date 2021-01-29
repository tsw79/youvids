<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 02:02
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {AssociateRepository, RepositoryInterface};
use phpchassis\lib\collections\Collection;
use youvids\domain\entities\Comment;

/**
 * Class CommentRepo
 * @package youvids\data\repositories
 */
class CommentRepo extends AssociateRepository implements RepositoryInterface {

    /**
     * CommentRepo constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = Comment::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Adds one record to the Comment table
     * @param int $videoId
     * @param string $body
     * @param int $responseToId
     * @return int
     */
    public function addOne(int $videoId, string $body, $responseToId = null) {

        return $this->add([
            "video_id"       => $videoId,
            "response_to_id" => $responseToId,
            "body"           => $body
        ]);
    }

    /**
     * Returns the number of comments for a particular video
     * @param int $videoId
     * @return int
     */
    public function countByVideo(int $videoId): int {
        return $this->count(["video_id" => $videoId]);
    }

    /**
     * Returns the number of comments that responded to a given parent comment
     * @param int $commentId
     * @return int
     */
    public function countByResponseTo(int $commentId): int {
        return $this->count(["response_to_id" => $commentId]);
    }

    /**
     * Alias for method: countByResponseTo
     * @param int $commentId
     * @return int
     */
    public function countReplies(int $commentId): int {
        return $this->countByResponseTo($commentId);
    }

    /**
     * Returns all comments for a particular video
     * @param int $videoId
     * @return Collection
     */
    public function findAllByVideo(int $videoId): Collection {

        $conditions = ["video_id" => $videoId];
        $params = [ "order" => "created DESC"];
        return $this->findAll($conditions, $params);
    }

    /**
     * Returns all PARENT comments for a particular video
     * @param int $videoId
     * @return null|Collection
     */
    public function findAllParentsByVideo(int $videoId): ?Collection {

        $conditions = ["video_id" => $videoId, "response_to_id" => null];
        $params = [ "order" => "created DESC"];
        return $this->findAll($conditions, $params);
    }

    /**
     * Returns a collection of comments replied to, for a particular comment
     * @param int $commentId
     * @return Collection
     */
    public function findAllByResponseTo(int $commentId)/*: Collection*/ {

        $conditions = ["response_to_id" => $commentId];
        $params = [ "order" => "created ASC"];
        return $this->findAll($conditions, $params);
    }

    /**
     * Return all replies for a given comment
     * @alias allByResponseTo()
     * @param int $commentId
     * @return string
     */
    public function findAllReplies(int $commentId)/*: Collection*/ {
        return $this->findAllByResponseTo($commentId);
    }

    /**
     * Checks whether a user has previously liked a particular comment
     * @param int $commentId
     * @param int $userId
     * @return bool
     */
    public function wasLikedBy(int $commentId, int $userId): bool {
        return $this->associateRepos->like->countNumTimesByComment($commentId, $userId);
    }

    /**
     * Checks whether a user has previously disliked a particular comment
     * @param int $commentId
     * @param int $userId
     * @return mixed
     */
    public function wasDislikedBy(int $commentId, int $userId): bool {
        return $this->associateRepos->dislike->countNumTimesByComment($commentId, $userId);
    }

    /**
     * Returns the difference between likes and dislikes for a particular comment
     * @param int $commentId
     * @return float
     */
    public function countLikesDiff(int $commentId): float {

        $numLikes = $this->associateRepos->like->countByComment($commentId);
        $numDislikes = $this->associateRepos->dislike->countByComment($commentId);
        return $numLikes - $numDislikes;
    }

    /**
     * Returns a number based on whether the user previously liked the given comment or nor
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function like(int $commentId, int $userId) {

        if ($this->wasLikedBy($commentId, $userId)) {

            $this->unLike($commentId, $userId);
            return -1;
        }
        else {

            $rowCount = $this->unDislike($commentId, $userId);
            $this->associateRepos->like->addOne($commentId, $userId);
            return 1 + $rowCount;
        }
    }

    /**
     * Returns a number based on whether the user previously disliked the given comment or nor
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function dislike(int $commentId, int $userId) {

        if ($this->wasDislikedBy($commentId, $userId)) {

            $this->unDislike($commentId, $userId);
            return 1;
        }
        else {

            $rowCount = $this->unLike($commentId, $userId);
            $this->associateRepos->dislike->addOne($commentId, $userId);
            return -1 - $rowCount;
        }
    }

    /**
     * Undo a like comment
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function unLike(int $commentId, int $userId): int {

        return $this->associateRepos->like->removeByComposites([
            "comment_id" => $commentId,
            "user_id"    => $userId
        ]);
    }

    /**
     * Undo a dislike comment
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function unDislike(int $commentId, int $userId): int {

        return $this->associateRepos->dislike->removeByComposites([
            "comment_id" => $commentId,
            "user_id"    => $userId
        ]);
    }
}