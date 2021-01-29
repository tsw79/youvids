<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 02:04
 */
namespace youvids\domain\mappers;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\{
    AbstractDataMapper, BaseDataMapper, DataMapperInterface
};
use youvids\domain\entities\Comment;

/**
 * Class CommentDataMapper
 * @package youvids\data\mappers
 */
class CommentMapper extends BaseDataMapper implements DataMapperInterface {

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
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $comment = null;

        if(!empty($data)) {
            $comment = new Comment();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $comment->$method($data[$dbColumn]);
            }
        }
        return $comment;
    }
}