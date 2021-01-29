<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/14/2019
 * Time: 10:47
 */
namespace phpchassis\data\entity;

use phpchassis\data\mapper\DataMapperInterface;

/**
 * Interface ModelInterface
 */
interface EntityInterface {

    /**
     * @param null $tableName
     * @return mixed
     */
    public static function tableName();

    /**
     * Returns the associated data mapper for a particular Entity
     * @return DataMapperInterface
     */
    public function dataMapper(): DataMapperInterface;
}