<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/12/2019
 * Time: 20:09
 */
namespace phpchassis\data\entity;

use phpchassis\data\mapper\DataMapperInterface;

/**
 * Class BaseEntity
 * @package phpchassis\data\entity
 */
abstract class BaseEntity implements EntityInterface {

    /**
     * @var int
     */
    protected $id = null;

    /**
     * @var string DateTime
     */
    protected $created;

    /**
     * @var int
     */
    protected $createdBy;

    /**
     * @var string DateTime
     */
    protected $modified;

    /**
     * @var int
     */
    protected $modifiedBy;

    /**
     * @var UserDataMapper $userMapper
     */
    protected $dataMapper = null;

    /**
     * @var string $tableName
     */
    protected static $tableName;

    /**
     * Returns the Fully Qualified Class Name of the currnt class
     * @return string
     */
    abstract public static function fqcn(): string;

    /**
     * Returns the associated data mapper for a particular Entity
     * @return DataMapperInterface
     */
    abstract public function dataMapper(): DataMapperInterface;

    /**
     * Sets rules for validation
     * @return array
     */
    abstract public function rules(): array;

//    public function __construct(DataMapperInterface $dataMapper) {
//        $this->dataMapper = $dataMapper;
//    }

    /**
     * Returns the name of the entity's db table
     *
     * @return string
     */
    public static function tableName() {
        return static::$tableName;
    }

    /**
     * Getter/Setter for id
     *
     * @param null $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function id($id = null) {

        if($id === null) {
            return $this->id;
        }
        else {
            if(!is_int($id) || $id < 1) {
                throw new InvalidArgumentException("The ID is invalid.");
            }
            $this->id = $id;
            return $this;
        }
    }

    /**
     * Getter/Setter for created
     * @param null $created
     * @return mixed
     */
    public function created($created = null) {

        if($created === null) {
            return $this->created;
        }
        else {
            $this->created = $created;
            return $this;
        }
    }

    /**
     * @alias created
     * @return mixed
     */
    public function signup() {
        return $this->created();
    }

    /**
     * Getter/Setter for createdBy
     *
     * @param null $createdBy
     * @return mixed
     */
    public function createdBy($createdBy = null) {

        if($createdBy === null) {
            return $this->createdBy;
        }
        else {
            $this->createdBy = $createdBy;
            return $this;
        }
    }

    /**
     * Getter/Setter for modified
     *
     * @param null $modified
     * @return mixed
     */
    public function modified($modified = null) {

        if($modified === null) {
            return $this->modified;
        }
        else {
            $this->modified = $modified;
            return $this;
        }
    }

    /**
     * Getter/Setter for modifiedBy
     *
     * @param null $modifiedBy
     * @return mixed
     */
    public function modifiedBy($modifiedBy = null) {

        if($modifiedBy === null) {
            return $this->modifiedBy;
        }
        else {
            $this->modifiedBy = $modifiedBy;
            return $this;
        }
    }

    /**
     * Returns the created date formatted for display
     * @return bool|string
     */
    public function displayCreated() {
        return $this->toDisplay($this->created);
    }

    public function displaySignup($format = null) {

        return $format == null
            ? $this->toDisplay($this->created)
            : $this->toDisplay($this->created, $format);
    }

    /**
     * Returns the modified date formatted for display
     * @return bool|string
     */
    public function displayModified() {
        return $this->toDisplay($this->modified);
    }

    /**
     * Alias for method displayCreated
     * @alias
     * @return bool|string
     */
    public function displayUploaded() {
        return $this->displayCreated();
    }

    /**
     * Returns the uploaded date formatted to a timestamp
     * @param $timestamp
     * @param string $format
     * @return bool|string
     */
    public function displayUploadedTimestamp(string $format = "M jS, Y") {
        return $this->toDisplay($this->created, $format);
    }

    /**
     * Returns a formatted date/timestamp by a given format
     * @param $dateTime
     * @param string $format
     * @return bool|string
     */
    private function toDisplay($dateTime, string $format = "M j, Y") {
        return date($format, strtotime($dateTime));
    }
}