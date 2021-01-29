<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 03:10
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use youvids\domain\entities\CommentLike;
use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};

/**
 * Class CommentLikeDataMapper
 * @package youvids\data\mappers
 */
class CommentLikeMapper extends BaseDataMapper implements DataMapperInterface {

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
        $commentLike = null;

        if(!empty($data)) {
            $commentLike = new CommentLike();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $commentLike->$method($data[$dbColumn]);
            }
        }
        return $commentLike;
    }
}