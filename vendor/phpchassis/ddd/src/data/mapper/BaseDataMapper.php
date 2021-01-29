<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/12/2019
 * Time: 20:19
 */
namespace phpchassis\data\mapper;
use phpchassis\data\entity\EntityInterface;

/**
 * Class BaseDataMapper
 * @package phpchassis\data\mapper
 */
abstract class BaseDataMapper {

    /**
     * Entity's Fully Qualified Class Name (FQCN)
     * @var $entityFQCN
     */
    protected $entityFQCN;

    /**
     * @var $mapping
     */
    protected $mappings = array();

    /**
     * @var array $defaultMappings
     */
    protected $defaultMappings = [
        'id'          => 'id',
        'created'     => 'created',
        'created_by'  => 'createdBy',
        'modified'    => 'modified',
        'modified_by' => 'modifiedBy'
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    abstract public function toEntity(array $data): ?EntityInterface;

    /**
     * Hydration method: produces an array from current instance property values
     * @return array
     */
    public function toArray(): array {

        $data = array();
        $mappings = array_merge($this->mappings, self::$defaultMappings);

        foreach ($mappings as $dbColumn => $propertyName) {
            $method = $propertyName;
            $data[$dbColumn] = $this->$method() ?? null;
        }
        return $data;
    }
}