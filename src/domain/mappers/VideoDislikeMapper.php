<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/12/2019
 * Time: 08:23
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use youvids\domain\entities\VideoDislike;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};

/**
 * Class VideoDislikeDataMapper
 * @package youvids\data\mappers
 */
class VideoDislikeMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'user_id'    => 'userId',
        'video_id'   => 'videoId'
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $videoDislike = null;

        if(!empty($data)) {
            $videoDislike = new VideoDislike();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $videoDislike->$method($data[$dbColumn]);
            }
        }
        return $videoDislike;
    }
}