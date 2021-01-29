<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 03:06
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\AbstractEntity;

/**
 * Class BaseCommentLike
 * @package youvids\data\entities
 */
class BaseCommentLike extends AbstractEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "comment_likes";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'user_id'    => 'userId',
        'comment_id' => 'commentId'
    ];

    /**
     * Sets rules for validation
     * @return array
     */
    public function rules(): array {
        return [];
    }
}