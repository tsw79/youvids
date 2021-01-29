<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 01:51
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\ {AbstractEntity, EntityInterface};

/**
 * Class BaseComment
 * @package youvids\data\entities
 */
class BaseComment extends AbstractEntity implements EntityInterface {

    /**
     * @var string $tableName
     */
    protected static $tableName = "comments";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        "video_id"       => "videoId",
        "response_to_id" => "responseToId",
        "body"           => "body"
    ];

    /**
     * Sets rules for validation
     */
    public function rules(): array {
        return [];
    }
}