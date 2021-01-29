<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/29/2019
 * Time: 18:31
 */
namespace youvids\domain\mappers;

use phpchassis\data\entity\EntityInterface;
use youvids\domain\entities\Category;
use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\mapper\ {BaseDataMapper, DataMapperInterface};

/**
 * Class CategoryDataMapper
 * @package youvids\data\mappers
 */
class CategoryMapper extends BaseDataMapper implements DataMapperInterface {

    /**
     * Entity mapping
     *
     * @var array $mapping
     */
    protected $mappings = [
        'name' => 'name'
    ];

    /**
     * Hydration method: populates values of this object instance from an array
     * @param array $data       Associative array
     * @return EntityInterface
     */
    public function toEntity(array $data): ?EntityInterface {

        $mappings = array_merge($this->mappings, $this->defaultMappings);
        $category = null;

        if(!empty($data)) {
            $category = new Category();

            foreach($mappings as $dbColumn => $propertyName) {
                $method = $propertyName;
                $category->$method($data[$dbColumn]);
            }
        }
        return $category;
    }
}