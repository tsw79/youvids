<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/28/2019
 * Time: 06:20
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {BaseRepository, RepositoryInterface};
use youvids\domain\entities\Category;

/**
 * Class CategoryRepo
 * @package youvids\data\repositories
 */
class CategoryRepo extends BaseRepository implements RepositoryInterface {

    /**
     * CategoryRepo constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = Category::fqcn();
        parent::__construct($dbAdapter);
    }
}