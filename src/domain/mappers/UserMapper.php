<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/18/2019
 * Time: 09:23
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};
use youvids\domain\entities\User;

/**
 * Class UserDataMapper
 * @package Youvids\data\mappers
 */
class UserMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        "first_name"        => "firstName",
        "last_name"         => "lastName",
        "username"          => "username",
        "email"             => "email",
        "password"          => "password",
        "profile_pic"       => "profilePic",
        "access_status"     => "accessStatus",
        "access_level"      => "accessLevel"
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $user = null;

        if(!empty($data)) {
            $user = new User();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $user->$method($data[$dbColumn]);
            }
        }
        return $user;
    }
}