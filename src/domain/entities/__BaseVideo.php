<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/2/2019
 * Time: 03:56
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\ {AbstractEntity, EntityInterface};

/**
 * Class BaseVideo
 * 
 * @package YouVids\data\entities
 */
class BaseVideo extends AbstractEntity implements EntityInterface {

    /**
     * @var string $tableName
     */
    protected static $tableName = "videos";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        'title'         => 'title',
        'description'   => 'description',
        'privacy'       => 'privacy',
        'file_path'     => 'filePath',
        'category_id'   => 'categoryId',
        'views'         => 'views',
        'duration'      => 'duration'
    ];

    /**
     * Sets rules for validation
     */
    public function rules(): array {
        return [];
    }
}