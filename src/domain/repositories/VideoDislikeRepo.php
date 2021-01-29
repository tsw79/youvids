<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 16:50
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {BaseRepository, RepositoryInterface};
use youvids\domain\entities\VideoDislike;

/**
 * Class VideoDislikeRepo
 * @package youvids\data\repositories
 */
class VideoDislikeRepo extends BaseRepository implements RepositoryInterface {

    /**
     * VideoDislikeDataMapper constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = VideoDislike::fqcn();
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
     * Returns the number of dislikes for a particular video
     * @param int $videoId
     * @return int
     */
    public function countByVideo(int $videoId): int {
        return $this->count(["video_id" => $videoId]);
    }

    /**
     * Returns the number of dislikes for a particular comment
     * @param int $commentId
     * @return int
     */
    public function countByComment(int $commentId): int {
        return $this->count(["comment_id" => $commentId]);
    }

    /**
     * Returns the count for number of times a particular video was disliked by a user
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
     * Returns the count for number of times a particular comment was disliked by a user
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
}