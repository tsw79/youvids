<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/23/2019
 * Time: 08:34
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};

/**
 * Class ThumbnailDataMapper
 * @package youvids\data\mappers
 */
class ThumbnailMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        "video_id"  => "videoId",
        "file_path" => "filePath",
        "selected"  => "selected"
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $thumbnail = null;

        if(!empty($data)) {
            $thumbnail = new Thumbnail();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $thumbnail->$method($data[$dbColumn]);
            }
        }
        return $thumbnail;
    }
}