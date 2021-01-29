<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 03:01
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\AbstractEntity;

/**
 * Class BaseCommentDislike
 * @package youvids\data\entities
 */
class BaseCommentDislike extends AbstractEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "comment_dislikes";

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