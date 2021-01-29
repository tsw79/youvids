<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/20/2019
 * Time: 08:53
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\ {AbstractEntity, EntityInterface};

/**
 * Class BaseSubscriber
 * @package youvids\data\entities
 */
class BaseSubscriber extends AbstractEntity implements EntityInterface {

    /**
     * @var string $tableName
     */
    protected static $tableName = "subscribers";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'user_to_id'    => 'userToId',
        'user_from_id'  => 'userFromId'
    ];

    public function rules(): array {
        return array();
    }
}