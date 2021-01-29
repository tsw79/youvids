<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/14/2019
 * Time: 09:28
 */
namespace phpchassis\data\entity;

use phpchassis\validator\Validator;

/**
 * Class AbstractEntity
 * @package phpchassis\data
 */
abstract class AbstractEntity {

    protected $id = null;
    protected $created;
    protected $createdBy;
    protected $modified;
    protected $modifiedBy;

    /**
     * @var string $tableName
     */
    protected static $tableName;

    /**
     * @var $mapping
     */
    protected $mappings = array();

    /**
     * @var array $defaultMappings
     */
    protected static $defaultMappings = [
        'id'          => 'id',
        'created'     => 'created',
        'created_by'  => 'createdBy',
        'modified'    => 'modified',
        'modified_by' => 'modifiedBy'
    ];

    //abstract protected function mappings(): array;

    /**
     * Sets rules for validation
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Hydration method: populates values of this object instance from an array
     *
     * @param array $data       Associative array
     * @param Entity $entity    Entity class
     * @return bool|Base
     */
    //public function toEntity($data, Entity $entity) {
    public static function arrayToEntity($data, $entity) {

        $mappings = array_merge($entity->mappings(), self::$defaultMappings);

        if($data && is_array($data)) {

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $entity->$method($data[$dbColumn]);
            }
            return $entity;
        }
        return false;
    }

    /**
     * Hydration method: produces an array from current instance property values
     *
     * @return array
     */
    public function toArray() {

        $data = array();
        $mappings = array_merge($this->mappings(), self::$defaultMappings);

        foreach ($mappings as $dbColumn => $propertyName) {
            $method = $propertyName;
            $data[$dbColumn] = $this->$method() ?? null;
        }
        return $data;
    }

    /**
     * Returns the name of the entity's db table
     *
     * @return string
     */
    public static function tableName() {
        return static::$tableName;
    }

    /**
     * @return array
     */
    public function mappings(): array {
        return $this->mappings;
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