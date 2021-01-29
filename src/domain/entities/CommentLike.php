<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 03:05
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\CommentLikeMapper;

/**
 * Class CommentLike
 * @package youvids\data\entities
 */
class CommentLike extends BaseEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "comment_likes";

    /**
     * @var int $userId
     */
    private $userId;

    /**
     * @var int $commentId
     */
    private $commentId;

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
     * Getter/Setter for commentId
     * @param null $commentId
     * @return string
     */
    public function commentId($commentId = null) {

        if($commentId === null) {
            return $this->commentId;
        }
        else {
            $this->commentId = $commentId;
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
            $this->dataMapper = new CommentLikeMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}