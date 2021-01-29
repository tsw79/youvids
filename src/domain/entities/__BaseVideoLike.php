<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 04:16
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\AbstractEntity;

/**
 * Class BaseVideoLike
 * @package youvids\data\entities
 */
class BaseVideoLike extends AbstractEntity{

    /**
     * @var string $tableName
     */
    protected static $tableName = "video_likes";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'user_id'    => 'userId',
        'video_id'   => 'videoId'
    ];

    public function rules(): array {
        return array();
    }
}