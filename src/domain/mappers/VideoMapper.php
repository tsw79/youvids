<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/6/2019
 * Time: 07:32
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};
use youvids\domain\entities\Video;

/**
 * Class VideoDataMapper
 * @package youvids\data\mappers
 */
class VideoMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'title'         => 'title',
        'description'   => 'description',
        'privacy'       => 'privacy',
        'file_path'     => 'filePath',
        'category_id'   => 'categoryId',
        'views'         => 'views',
        'duration'      => 'duration'
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $video = null;

        if(!empty($data)) {
            $video = new Video();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $video->$method($data[$dbColumn]);
            }
        }
        return $video;
    }
}