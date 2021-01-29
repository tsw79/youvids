<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/23/2019
 * Time: 19:31
 */
namespace phpchassis\data\repository;

use phpchassis\data\db\FluentPdoAdapter;
use phpchassis\data\entity\EntityInterface;
use phpchassis\data\mapper\DataMapperInterface;
use phpchassis\lib\collections\Collection;
use phpchassis\validator\Validator;
use phpchassis\lib\traits\PhpCommons;
use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\db\query\ {QueryBuilder, QueryCondition};

/**
 * Class BaseRepository
 *
 * @package phpchassis\data
 */
abstract class BaseRepository {

    /*------------------------------------------------------------------------------------------------------------------
     * NOTE:
     * -----
     *
     * Data Storage Validation:
     *  - Is this field value not null?
     *  - Does this field value satisfy our unique constraint for this column?
     *  - Is this field less than 40 characters in length?
     *
     * Data Storage Validation belongs as part of the repository, because those constraints are specific
     * to how we're storing our data.
     *----------------------------------------------------------------------------------------------------------------*/

    use PhpCommons;

    /**
     * Holds a single instance of IDatabaseAdapter
     *
     * @var $dbAdapter
     */
    protected $dbAdapter = null;

    /**
     * Entity's Fully Qualified Class Name (FQCN)
     * @var $entityFQCN
     */
    protected $entityFQCN;

    /**
     * AbstractDataMapper constructor.
     *
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public  function __construct(DatabaseAdapterInterface $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * Creates an entity from array
     * @param array $row
     * @return mixed
     */
    protected function createEntity(array $row) {

        $entityClass = $this->entityFQCN;
        $entity = new $entityClass();
        return $entity->dataMapper()->toEntity($row);
    }

    /**
     * Creates a collection of entities
     * @param array $rows
     * @return Collection
     */
    protected function createEntityCollection(array $rows) {

        $collection = new Collection();

        foreach($rows as $row) {
            $collection->set($this->createEntity($row));
        }

        return $collection;
    }

    /**
     * Returns a Collection of a specific Entity
     * @param array|null $conditions
     * @param array|null $params
     * @return null|Collection
     */
    public function findAll($conditions = null, array $params = null): ?Collection {

        $entityClass = $this->entityFQCN;
        $cexpression = null;
        $cparams = [];

        if (!is_string($conditions)) {

            if ((null !== $conditions) && $this->array_key_isset("cexpression", $conditions) && $this->array_key_isset("cparams", $conditions)) {
                $cexpression = $conditions["cexpression"];
                $cparams = $conditions["cparams"];
            }
        }

        $rows = $this->dbAdapter->load($entityClass::tableName())
            ->withConditions(
                $cexpression ?? $conditions,
                $cparams
            )
            ->withParams($params)
            ->all();  

        return $this->createEntityCollection($rows);
    }

    /**
     * Returns an EntityInterface loaded from the db
     * @param array $conditions
     * @param array|null $params
     * @return null|EntityInterface
     */
    public function find(array $conditions, array $params = null): ?EntityInterface {

        $entityClass = $this->entityFQCN;
        $row = $this->dbAdapter->load($entityClass::tableName())
            ->withConditions($conditions)
            ->withParams($params)
            ->one();

        return !$row ? null : $this->createEntity($row);
    }

    /**
     * Returns a record based on a field name and its value
     * @param string $fieldName
     * @param $value
     * @return null|EntityInterface
     */
    public function findBy(string $fieldName, $value): ?EntityInterface {
        return $this->find([$fieldName => $value]);
    }

    /**
     * Returns a record based on a field name and its value
     * @param string $fieldName
     * @param $value
     * @return null|EntityInterface
     */
    public function findById(int $id): ?EntityInterface {
        return $this->find(["id" => $id]);
    }

    /**
     * Returns a single column from a specific table in the Db
     * @param string $attribute
     * @param array|null $conditions
     * @param array|null $params
     * @return mixed
     */
    public function findByColumn(string $column, array $conditions = null, array $params = null) {

        $entityClass = $this->entityFQCN;
        $column = $this->dbAdapter->load($entityClass::tableName())
            ->withConditions($conditions)
            ->withParams($params)
            ->one($column);

        return $column;
    }

    /**
     * Returns a a list of columns from a specific table in the Db
     * @param array $columns
     * @param array|null $conditions
     * @param array|null $params
     * @return array|null
     */
    public function findAllByColumn(string $column, array $conditions = null, array $params = null): ?array {

        $entityClass = $this->entityFQCN;
        $values = $this->dbAdapter->load(
                $entityClass::tableName(),
                array($column),
                FluentPdoAdapter::CLEAR_SELECT_ALL
            )
            ->withConditions($conditions)
            ->withParams($params)
            ->all($column);

        /*
         The above query retrieves data in the following format:
            array (
                146 => [
                    'video_id' => 146,
                ],
                111 => [
                    'video_id' => 111
                ]
            )

            Note: In order to return an associative array with ONLY video ids, all the array keys had been filled with
                  the actual id of the video. 
            //TODO Need to find a more elegant way of doing this!!
         */

        return array_keys($values);
    }

    /**
     * Adds a record to the data source
     * @param array $data
     * @return int
     */
    public function add(array $data): int {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->insert(
            $entityClass::tableName(),
            $data
        );
    }

    /**
     * Edits a record in the data source
     * @param EntityInterface|QueryCondition $object
     * @return int
     */
    public function edit(array $setData, array $conditions): int {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->update(
            $entityClass::tableName(),
            $setData,
            $conditions);
    }

    /**
     * Returns the total number of values in the specified field
     * @param array $conditions
     * @return int
     */
    public function count(array $conditions): int {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->count(
            $entityClass::tableName(),
            $conditions
        );
    }

    /**
     * Returns the sum of all the values in the specified column
     * @param string $column
     * @param array|null $conditions
     * @return mixed
     */
    public function sum(string $column, array $conditions = null) {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->sum(
            $entityClass::tableName(),
            $column,
            $conditions
        );
    }

    /**
     * Returns the largest value from the specified table's field
     * @param string $column
     * @param array|null $conditions
     */
    public function avg(string $column, array $conditions = null) {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->avg(
            $entityClass::tableName(),
            $column,
            $conditions
        );
    }

    /**
     * Returns the largest value from the specified table's field
     * @param string $column
     * @param array|null $conditions
     * @return mixed
     */
    public function max(string $column, array $conditions = null) {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->max(
            $entityClass::tableName(),
            $column,
            $conditions
        );
    }

    /**
     * Returns the smallest value in the specified table field
     * @param string $column
     * @param array|null $conditions
     * @return mixed
     */
    public function min(string $column, array $conditions = null) {

        $entityClass = $this->entityFQCN;
        return $this->dbAdapter->min(
            $entityClass::tableName(),
            $column,
            $conditions
        );
    }

    /**
     * Querying the db with raw custom sql
     * @param string $sql
     * @param array|null $bindings
     * @return mixed
     */
    public function  rawQuery(string $sql, array $bindings = null) {
        return $this->dbAdapter->query($sql, $bindings);
    }







    // @TODO Need to remove these below once all methods have been rewritten!!
    // ---------------------------------------------------------------------------------------------------------------

    /**
     * Deletes a record(s) by its primary key from a particular db table
     * @param int $id   Id of table's Primary key
     * @return int      Number of records affected
     */
    public function removeById(int $id): int {
        return $this->deleteBy("id", $id);
    }

    /**
     * Deletes a record(s) by its composite keys from a particular db table
     * @param array $params     Composite keys and their values
     * @return int              Number of records affected
     */
    public function removeByComposites(array $params): int {

        if (empty($params)) {
            throw new \InvalidArgumentException("You must pass paramaters with values, received empty.");
        }

        $numParams = count($params);

        if ($numParams < 2 || $numParams > 2) {
            throw new \OutOfRangeException("Expecting 2 composite keys!");
        }

        return $this->dataMapper->delete($params);
    }

    /**
     * Deletes a record(s) by a particular key (field) and its value
     * @param string $name
     * @param $value
     * @return int
     */
    public function removeBy(string $name, $value): int {
        return $this->dataMapper->delete([$name => $value]);
    }
}