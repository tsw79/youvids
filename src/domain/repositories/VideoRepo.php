<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 16:52
 */
namespace youvids\domain\repositories;

use Envms\FluentPDO\Literal;
use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {AssociateRepository, RepositoryInterface};
use phpchassis\lib\collections\Collection;
use youvids\domain\entities\Video;

/**
 * Class VideoRepo
 * @package src\data\repositories
 */
class VideoRepo extends AssociateRepository implements RepositoryInterface {

    /**
     * CategoryDataMapper constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = Video::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Inserts/adds a single row to the thumbnail table in the db
     * @param $title
     * @param $description
     * @param $privacy
     * @param $filePath
     * @param $categoryId
     * @param $duration
     * @return int
     */
    public function newRecord(string $title, string $description, int $privacy, string $filePath, int $categoryId, string $duration): int {

        return $this->add([
            "title"       => $title,
            "description" => $description,
            "privacy"     => $privacy,
            "file_path"   => $filePath,
            "category_id" => $categoryId,
            "duration"    => $duration
        ]);
    }

    /**
     * Returns all videos for the user who uploaded it
     * @param int $uploadedById
     * @return Collection
     */
    public function findAllByUploadedUser(int $uploadedById) {

        return $this->findAll([
           "created_by" => $uploadedById
        ]);
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function findAllRandomly(int $limit = 15) {

        return $this->findAll(null, [
                "order" => "RAND()",
                "limit" => 15
            ]
        );
    }

    /**
     * @param int $videoId
     * @return string
     */
    public function thumbnailPath(int $videoId): string {
        return $this->associateRepos->thumbnail->pathByVideo($videoId);
    }

    /**
     * Increments the view count for a particular video by 1
     * @param int $id   Video Id
     * @return mixed
     */
    public function incrementViews(int $id) {

        $setData = ["views" => new Literal("views + 1")];
        $condition = ["id" => $id];
        return $this->edit($setData, $condition);
    }

    /**
     * Returns a json encoded result based on whether the user previously has liked the particular video or not
     * @param int $videoId
     * @param int $userId
     * @return string
     */
    public function like(int $videoId, int $userId): string {

        if ($this->wasLikedBy($videoId, $userId)) {
            // User has already liked this particular video
            $this->undoLike($videoId, $userId);
            $result = [
                "likes"    => -1,
                "dislikes" => 0
            ];
        }
        else {
            $rowCount = $this->undoDislike($videoId, $userId);
            $this->associateRepos->videoLike->newRecord($videoId, $userId);
            $result = [
                "likes"    => 1,
                "dislikes" => 0 - $rowCount
            ];
        }
        return json_encode($result);
    }

    /**
     * Returns a json encoded result based on whether the user previously has dislikes the particular video or not
     * @param int $videoId
     * @param int $userId
     * @return string
     */
    public function dislike(int $videoId, int $userId): string {

        if ($this->wasDislikedBy($videoId, $userId)) {
            // User has already disliked this particular video
            $this->undoDislike($videoId, $userId);
            $result = [
                "likes"     => 0,
                "dislikes"  => -1
            ];
        }
        else {
            $rowCount = $this->undoLike($videoId, $userId);
            $this->associateRepos->videoDislike->newRecord($videoId, $userId);
            $result = [
                "likes"     => 0 - $rowCount,
                "dislikes"  => 1
            ];
        }
        return json_encode($result);
    }

    /**
     * Undo a like for a particular video
     * @param int $videoId
     * @param int $userId
     * @return int
     */
    public function undoLike(int $videoId, int $userId): int {

        return $this->associateRepos->videoLike->removeByComposites([
            "video_id"  =>  $videoId,
            "user_id"   =>  $userId
        ]);
    }

    /**
     * Undo a dislike for a particular video
     * @param int $videoId
     * @param int $userId
     * @return int
     */
    public function undoDislike(int $videoId, int $userId): int {

        return $this->associateRepos->videoDislike->removeByComposites([
            "video_id"  =>  $videoId,
            "user_id"   =>  $userId
        ]);
    }

    /**
     * Returns the number of likes for a given video
     * @param int $id   Video Id
     * @return int
     */
    public function numLikes(int $id): int {    
        return $this->associateRepos->videoLike->countByVideo($id);
    }

    /**
     * Returns the number of dislikes for a given video
     * @param int $id   Video Id
     * @return int
     */
    public function numDislikes(int $id): int {
        return $this->associateRepos->videoDislike->countByVideo($id);
    }

    /**
     * Checks whether the logged-in user has previously liked the particular video
     * @param int $videoId
     * @param int $userId
     * @return bool
     */
    public function wasLikedBy(int $videoId, int $userId): bool {
        return $this->associateRepos->videoLike->countNumTimesByVideo($videoId, $userId);
    }

    /**
     * Checks whether the logged-in user has previously disliked the particular video
     * @param int $videoId
     * @param int $userId
     * @return bool
     */
    public function wasDislikedBy(int $videoId, int $userId): bool {
        return $this->associateRepos->videoDislike->countNumTimesByVideo($videoId, $userId);
    }

    /**
     * Returns the number of comments for the
     * @param int $videoId
     * @return int
     */
    public function countComments(int $videoId): int {
        return $this->associateRepos->comment->countByVideo($videoId);
    }

    /**
     * Returns a collection of videos for a particular video
     * @param int $videoId
     * @return Collection
     */
    public function comments(int $videoId): Collection {
        return $this->associateRepos->comment->allByVideo($videoId);
    }

    /**
     * Returns the SUM of views for a given User
     * @param int $uploadedById
     * @return int
     */
    public function findSumViewsByUploadedUser(int $uploadedById): int {

        return $this->sum("views", [
            "created_by" => $uploadedById
        ]);
    }

    /**
     * Returns all videos for a subscriber by his Subscription Ids
     *    Query:
     *      SELECT *
     *      FROM videos
     *      WHERE created_by IN (?)
     * 
     * @param array $ids
     * @return mixed
     */
    public function findAllBySubscriptionIds(array $ids): ?Collection {

        $conditions = [
            "cexpression" => "created_by IN (?)",
            "cparams"     => $ids
        ];
        return $this->findAll($conditions);
    }

    /**
     * Returns all videos for a subscriber by a List of Subscription Users
     *    Query:
     *      Placement holdders: user1, user2, user3
     *        SELECT * FROM videos WHERE uploadedBy = ? OR uploadedBy = ? OR uploadedBy = ?
     *          $query->bindParam(1, "user1");
     *          $query->bindParam(2, "user2");
     *          $query->bindParam(3, "user3");
     * 
     * @param Collection $subscriptions     User
     * @return Collection
     */
    public function findAllBySubscriptionUsers(Collection $subscriptions) {

        if (sizeof($subscriptions) > 1) {

            $subscriptionIds = array();
            foreach ($subscriptions as $subscription) {
                $subscriptionIds[] = $subscription->id();
            }
            return $this->findAllBySubscriptionIds($subscriptionIds);
        }
        else {
            return $this->findAllByUploadedUser($subscriptions[0]->id());
        }
    }

    /**
     * Returns all trending videos
     *    Query:
     *      SELECT *
     *      FROM videos
     *      WHERE uploadDate >= now() - INTERVAL 7 DAY
     *      ORDER BY views DESC LIMIT 15
     * 
     * @return null|Collection
     */
    public function findAllTrending(): ?Collection {

        $condition = "created >= NOW() - INTERVAL 7 DAY";

        return $this->findAll($condition, [
            "order" => "views",
            "limit" => 15
        ]);
    }

    /**
     * Edits a record in the data source
     * @param int $id
     * @param string $title
     * @param string $description
     * @param int $privacy
     * @param int $category
     * @return int
     */
    public function editRecord(int $id, string $title, string $description, int $privacy, int $category) {

        $setData = [
            "title"       => $title,
            "description" => $description,
            "privacy"     => $privacy,
            "category"    => $category
        ];
        return $this->edit($setData, [
            "id" => $id
        ]);
    }

    /**
     * Searches the data source for a given search criteria
     * 
     *    //TODO Need to fix me!!!
     *    Query: 
     *      SELECT *
     *      FROM videos
     *      WHERE title LIKE CONCAT('%', :term, '%')
     *      OR uploadedBy LIKE CONCAT('%', :term, '%')
     *      ORDER BY $orderBy DESC
     * 
     * @param string $searchTerm
     * @param string $orderBy
     * @return null|Collection
     */
    public function search(string $searchTerm, string $orderBy): ?Collection {

        $conditions = [
            "cexpression" => "title LIKE CONCAT(:term)",  // title LIKE CONCAT(:term) OR created_by LIKE CONCAT(:term)
            "cparams"     => [":term" => "%$searchTerm%"]
        ];
        return $this->findAll($conditions, [
            "order" => $orderBy
        ]);
    }

    /**
     * Returns all videos the given User has liked
     * @param int $userId
     * @return null|Collection
     */
    public function findAllLikesByUser(int $userId): ?Collection {

        $videos = null;
        $videoIds = $this->associateRepos->videoLike->findAllVideoIdsByUser($userId);

        if ($videoIds) {
            $videos = new Collection();
            foreach ($videoIds as $videoId) {
                $videos->set($this->findById($videoId));
            }
        }
        return $videos;
    }
}