<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 03:30
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\VideoLikeMapper;

/**
 * Class VideoLike
 * @package youvids\data\entities
 */
class VideoLike extends BaseEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "video_likes";

    /**
     * @var int $userId
     */
    private $userId;

    /**
     * @var int $videoId
     */
    private $videoId;

    /**
     * Getter/Setter for userId
     * @param null $userId
     * @return string
     */
    public function userId($userId = null) {

        if($userId === null) {
            return $this->userId;
        }
        else {
            $this->userId = $userId;
        }
    }

    /**
     * Getter/Setter for videoId
     * @param null $videoId
     * @return string
     */
    public function videoId($videoId = null) {

        if($videoId === null) {
            return $this->videoId;
        }
        else {
            $this->videoId = $videoId;
        }
    }

    /**
     * Returns the Fully Qualified Class Name
     * @return string
     */
    public static function fqcn(): string {
        return self::class;
    }

    /**
     * Returns the associated data mapper for a particular Entity
     * @return DataMapperInterface
     */
    public function dataMapper(): DataMapperInterface {

        if (null == $this->dataMapper) {
            $this->dataMapper = new VideoLikeMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}