<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/23/2019
 * Time: 08:32
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {BaseRepository, RepositoryInterface};
use phpchassis\lib\collections\Collection;
use youvids\domain\entities\Thumbnail;

/**
 * Class ThumbnailRepo
 * @package youvids\data\repositories
 */
class ThumbnailRepo extends BaseRepository implements RepositoryInterface {

    const NOT_SELECTED = 0;
    const SELECTED = 1;

    /**
     * ThumbnailDataMapper constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = Thumbnail::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Adds a thumbnail to the Thumbnail table
     * @param array $params
     * @return int
     */
    public function addOne(int $videoId, string $filePath, int $selected): int {

        return $this->add([
            "video_id"  => $videoId,
            "file_path" => $filePath,
            "selected"  => $selected
        ]);
    }

    /**
     * Returns all thumbnails for a given video
     * @param int $videoId
     * @return Collection
     */
    public function findAllByVideoId(int $videoId): Collection {

        return $this->all([
            "video_id" => $videoId
        ]);
    }

    /**
     * Returns the thumbnail's path by the given video
     * @param int $videoId
     * @param int $selected
     * @return string
     */
    public function pathByVideo(int $videoId, int $selected = 1): string {

        return $this->findByColumn(
            "file_path",
            [
                "video_id" => $videoId,
                "selected" => 1
            ]);

//        $path = $this->dataMapper->custom(
//            "SELECT file_path as filePath
//              FROM thumbnails
//              WHERE video_id = ?
//              AND selected = 1",
//            [$videoId]
//        );
//
//        return $path["filePath"];
    }

    /**
     * UnSets (setting selected attribute to 0) all thumbnails for a given video
     * @param int $videoId
     * @return int
     */
    public function unsetSelected(int $videoId): int {

        return  $this->dataMapper->custom(
            "UPDATE thumbnails
                SET selected = 0
                WHERE video_id = ?",
            [$videoId]
        );
    }

    /**
     * Sets (setting selected attribute to 1) a given thumbnail to selected
     * @param int $id   Thumbnail Id
     * @return int
     */
    public function setSelected(int $id): int {

        return  $this->dataMapper->custom(
            "UPDATE thumbnails
                SET selected = 1
                WHERE id = ?",
            [$id]
        );
    }
}