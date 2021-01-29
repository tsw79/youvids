<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/20/2019
 * Time: 08:52
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\SubscriberMapper;

/**
 * Class Subscriber
 * @package youvids\data\entities
 */
class Subscriber extends BaseEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "subscribers";

    /**
     * @var int $userTo
     */
    private $userToId;

    /**
     * @var int $userTo
     */
    private $userFromId;

    /**
     * Getter/Setter for userToId
     * @param null $userToId
     * @return int
     */
    public function userToId($userToId = null) {

        if($userToId === null) {
            return $this->userToId;
        }
        else {
            $this->userToId = $userToId;
        }
    }

    /**
     * Getter/Setter for userFromId
     * @param null $userFromId
     * @return int
     */
    public function userFromId($userFromId = null) {

        if($userFromId === null) {
            return $this->userFromId;
        }
        else {
            $this->userFromId = $userFromId;
        }
    }

    /**
     * Alias for method userToId
     * @return int
     */
    public function userSubscribedToId(): int {
        return $this->userToId();
    }

    /**
     * Returns the Fully Qualified Class Name
     * @return string
     */
    public static function fqcn(): string {
        return self::class;
    }

    /**
     * Returns the associated data mapper for a particular Entity
     * @return DataMapperInterface
     */
    public function dataMapper(): DataMapperInterface {

        if (null == $this->dataMapper) {
            $this->dataMapper = new SubscriberMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}