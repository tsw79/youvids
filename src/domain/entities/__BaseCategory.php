<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/2/2019
 * Time: 02:56
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\AbstractEntity;

/**
 * Class BaseCategory
 */
class BaseCategory extends AbstractEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "categories";

    /**
     * Entity mapping
     *
     * @var array $mapping
     */
    protected $mappings = ['name' => 'name'];

    /**
     * Sets rules for validation
     */
    public function rules(): array {
        return [];
    }
}