<?php

/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/4/2019
 * Time: 16:24
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\AbstractEntity;

/**
 * Class BaseThumbnail
 * @package YouVids\data\dto
 */
class BaseThumbnail extends AbstractEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "thumbnails";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        "video_id"  => "videoId",
        "file_path" => "filePath",
        "selected"  => "selected"
    ];

    public function rules(): array {
        return [];
    }
}