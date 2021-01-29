<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/23/2019
 * Time: 20:14
 */
namespace phpchassis\data\mapper;

use phpchassis\data\entity\EntityInterface;

/**
 * Interface DataMapperInterface
 *
 * @package PhpChassis\data\mapper
 */
interface DataMapperInterface {

    /**
     * @return array
     */
//    public function mappings(): array;

    public function toEntity(array $data): ?EntityInterface;

    /**
     * Hydration method: produces an array from current instance property values
     * @return array
     */
    public function toArray(): array;
}