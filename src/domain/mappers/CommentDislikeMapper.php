<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 03:12
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};
use youvids\domain\entities\CommentDislike;

/**
 * Class CommentDislikeDataMapper
 * @package youvids\data\mappers
 */
class CommentDislikeMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'user_id'    => 'userId',
        'comment_id' => 'commentId'
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $commentDislike = null;

        if(!empty($data)) {
            $commentDislike = new CommentDislike();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $commentDislike->$method($data[$dbColumn]);
            }
        }
        return $commentDislike;
    }
}