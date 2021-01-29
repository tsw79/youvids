<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 16:48
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {BaseRepository, RepositoryInterface};
use youvids\domain\entities\VideoLike;

/**
 * Class VideoLikeRepo
 * @package youvids\data\repositories
 */
class VideoLikeRepo extends BaseRepository implements RepositoryInterface {

    /**
     * VideoLikeRepo constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = VideoLike::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Adds a new record to the data source
     * @param int $videoId
     * @param int $userId
     * @return int
     */
    public function newRecord(int $videoId, int $userId): int {

        return $this->add([
            "video_id" => $videoId,
            "user_id"  => $userId
        ]);
    }

    /**
     * Returns the number of likes for a particular video
     * @param int $videoId
     * @return int
     */
    public function countByVideo(int $videoId): int {
        return $this->count(["video_id" => $videoId]);
    }

    /**
     * Returns the number of likes for a particular comment
     * @param int $commentId
     * @return int
     */
    public function countByComment(int $commentId): int {
        return $this->count(["comment_id" => $commentId]);
    }

    /**
     * Returns the count for number of times a particular video was liked by a user
     * @param int $videoId
     * @param int $userId
     * @return int
     */
    public function countNumTimesByVideo(int $videoId, int $userId): int {

        return $this->count([
            "user_id"   =>  $userId,
            "video_id"  =>  $videoId
        ]);
    }

    /**
     * Returns the count for number of times a particular comment was liked by a user
     * @param int $commentId
     * @param int $userId
     * @return int
     */
    public function countNumTimesByComment(int $commentId, int $userId): int {

        return $this->count([
            "user_id"    =>  $userId,
            "comment_id" =>  $commentId
        ]);
    }

    /**
     * Returns all Video Id's for a given User
     * @param int $userId
     * @return array|null
     */
    public function findAllVideoIdsByUser(int $userId)/*: ?array*/ {

        $conditions = ["user_id" => $userId];
        $params = ["order" => "id DESC"];
        return $this->findAllByColumn("video_id", $conditions, $params);           exit;
    }
}