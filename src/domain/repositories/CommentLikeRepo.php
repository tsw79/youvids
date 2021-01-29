<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 03:08
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {BaseRepository, RepositoryInterface};
use youvids\domain\entities\CommentLike;

/**
 * Class CommentLikeRepo
 * @package youvids\data\repositories
 */
class CommentLikeRepo extends BaseRepository implements RepositoryInterface {

    /**
     * CommentLikeDataMapper constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = CommentLike::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Adds a record to the comment like table
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function addOne(int $commentId, int $userId): int {

        return $this->add([
            "comment_id" => $commentId,
            "user_id"    => $userId
        ]);
    }

    /**
     * Returns the number of like for a given comment and user
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function countNumTimesByComment(int $commentId, int $userId): int {

        return $this->count([
            "comment_id" => $commentId,
            "user_id"    => $userId
        ]);
    }

    /**
     * Returns the number of likes for a given comment
     * @param int $commentId
     * @return int
     */
    public function countByComment(int $commentId): int {

        return $this->count([
            "comment_id" => $commentId
        ]);
    }
}