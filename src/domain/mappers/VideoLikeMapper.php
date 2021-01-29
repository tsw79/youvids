<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 17:16
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};
use youvids\domain\entities\VideoLike;

/**
 * Class VideoLikeDataMapper
 * @package youvids\data\mappers
 */
class VideoLikeMapper extends BaseDataMapper implements DataMapperInterface {

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
        $videoLike = null;

        if(!empty($data)) {
            $videoLike = new VideoLike();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $videoLike->$method($data[$dbColumn]);
            }
        }
        return $videoLike;
    }
}