<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/21/2019
 * Time: 03:21
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};
use youvids\domain\entities\Subscriber;

/**
 * Class SubscriberDataMapper
 * @package youvids\data\mappers
 */
class SubscriberMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'user_to_id'    => 'userToId',
        'user_from_id'  => 'userFromId'
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $subscriber = null;

        if(!empty($data)) {
            $subscriber = new Subscriber();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $subscriber->$method($data[$dbColumn]);
            }
        }
        return $subscriber;
    }
}