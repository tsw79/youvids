<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/23/2019
 * Time: 20:08
 */
namespace phpchassis\data\repository;

use phpchassis\data\db\query\QueryCondition;
use phpchassis\data\entity\EntityInterface;
use phpchassis\lib\collections\Collection;

/**
 * Interface RepositoryInterface
 *
 * @package PhpChassis\data
 */
interface RepositoryInterface {

//    public function load(QueryCondition $qryCondition, bool $one);
//
//    public function loadOne(QueryCondition $qryCondition);
//
    public function findAll($conditions = null, array $params = null): ?Collection;
//
//    public function loadBy(string $name, $value);
//
//    public function loadById(int $id);
//
//    public function count(QueryCondition $qryCondition): int;
//
//    public function save(object $object): int;
//
//    public function insert(object $object): int;
//
//    public function update(object $object): int;
//
//    public function delete(array $params): int;
//
//    public function custom($sql, array $dataBindings = null, bool $one = true);
}