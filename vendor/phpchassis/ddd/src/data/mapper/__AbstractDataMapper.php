<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/18/2019
 * Time: 08:57
 */
namespace phpchassis\data\mapper;

use phpchassis\traits\PhpCommons;
use phpchassis\lib\collections\Collection;
use phpchassis\data\entity\EntityInterface;
use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\db\query\ {QueryBuilder, QueryCondition};

/**
 * Class AbstractDataMapper
 */
abstract class AbstractDataMapper {

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
        return $entityClass::arrayToEntity($row, new $entityClass());
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
     * Count the number of rows for a particular sql condition
     * @param QueryCondition $qryCondition
     * @return int
     * @throws \phpchassis\data\db\query\pdo\QueryException
     * @throws \phpchassis\exceptions\QueryException
     */
    public function count(QueryCondition $qryCondition): int {

        $entityClass = $this->entityFQCN;
        $qryBuilder = QueryBuilder::select($entityClass::tableName(), "count(*) as total");

        if ($qryCondition->isHashCondition()) {

            $counter = 1;

            foreach ($qryCondition->conditions() as $field => $value) {

                if ($counter == 1) {
                    $qryBuilder->where($field, $value);
                }
                else {
                    // Hash conditions will always use the AND logical operator
                    $qryBuilder->and($field, $value);
                }
                ++$counter;
            }
        }
        elseif ($qryCondition->isOperatorCondition()) {
            // @TODO Implement the Operator Condition
        }

        $sql = $qryBuilder->build();
        $row = $this->dbAdapter->fetch($sql, $qryBuilder->dataBindings());
        return (is_array($row) && $this->array_key_isset("total", $row)) ? $row["total"] : false;
    }

    public function custom($sql, array $dataBindings = null, bool $one = true) {

        if($one) {
            return $this->dbAdapter->fetch($sql, $dataBindings);
        }
        else {
            return $this->dbAdapter->fetchAll($sql, $dataBindings);
        }
    }

    public function customUpdate($sql, array $dataBindings = null) {    
        return $this->dbAdapter->update($sql, $dataBindings);
    }

    /**
     * Loads a particular entity or a collection of entities
     * @param QueryCondition $qryCondition
     * @param bool $one
     * @return null|: EntityInterface|Collection
     * @throws \phpchassis\exceptions\QueryException
     */
    public function load(QueryCondition $qryCondition = null, bool $one) {

        $entityClass = $this->entityFQCN;
        $qryBuilder = QueryBuilder::select($entityClass::tableName());

        if (!is_null($qryCondition)) {

            if ($qryCondition->isHashCondition()) {

                $counter = 1;

                foreach ($qryCondition->conditions() as $field => $value) {

                    if ($counter == 1) {
                        $qryBuilder->where($field, $value);
                    }
                    else {
                        // Hash conditions will always use the AND logical operator
                        $qryBuilder->and($field, $value);
                    }
                    ++$counter;
                }
            } elseif ($qryCondition->isOperatorCondition()) {
                // @TODO Implement the Operator Condition
            }

            $qryParams = $qryCondition->params();

            if ($qryParams != null) {

                if ($this->array_key_isset("order", $qryParams)) {
                    $qryBuilder->order($qryParams["order"]);
                }

                if ($this->array_key_isset("limit", $qryParams)) {
                    $qryBuilder->limit($qryParams["limit"]);
                }
            }
        }

        $sql = $qryBuilder->build();

        // If $one is TRUE return a domain object, else a domain objects list.
        // If $one is TRUE then add limit to sql statement, or so...
        if($one) {
            $row = $this->dbAdapter->fetch($sql, $qryBuilder->dataBindings());
            return $row ? $this->createEntity($row) : null;
        }
        else {
            $rows = $this->dbAdapter->fetchAll($sql, $qryBuilder->dataBindings());
            return $rows ? $this->createEntityCollection($rows) : null;
        }
    }

    /**
     * Alias for method load
     * @param QueryCondition $qryCondition
     * @return bool|mixed|Collection
     */
    public function loadOne(QueryCondition $qryCondition): EntityInterface {
        return $this->load($qryCondition, true);
    }

    /**
     * Alias for method load
     * @param QueryCondition $qryCondition
     * @return bool|mixed|Collection
     */
    public function loadAll(QueryCondition $qryCondition = null)/*: Collection*/ {
        return $this->load($qryCondition, false);
    }

    /**
     * Saves (inserts/updates) a record to data source
     * @param EntityInterface|QueryCondition|array $object
     * @return int
     */
    public function save($object): int {

        if ($this->isNewRecord($object)) {
            return $this->insert($object);
        }
        else {
            return $this->update($object);
        }
    }

    /**
     * Inserts a new record to a particular entity's table
     * @param EntityInterface|array $object
     * @return int
     */
    public function insert($object): int {

        $entityClass = $this->entityFQCN;
        $qryBuilder = QueryBuilder::insert($entityClass::tableName());

        if ($object instanceof EntityInterface) {

            $array = $object->toArray();
            unset($array["id"]);        // The DBMS auto generates this field
            unset($array["modified"]);  // The DBMS can handle this field
        }
        elseif (is_array($object)) {
            $array = $object;
        }

        // Need to bind these values for auditing purposes
        $array["created"] = date('Y-m-d H:i:s');
        $array["created_by"] = 84;    // @TODO Need to get this for the logged in user
        $array["modified_by"] = 84;

        $sql = $qryBuilder
            ->values($array)
            ->build();

        // Need to prepare the data in array format so as to pass it to the adapter insert method
        $insertId = $this->dbAdapter->insert($sql, $qryBuilder->dataBindings());
        //$object->id($insertId);
        return $insertId;
    }

    /**
     * Updates a particular record
     * @param EntityInterface|QueryCondition $object
     * @return int
     * @throws \phpchassis\exceptions\QueryException
     */
    public function update(object $object): int {

        $entityClass = $this->entityFQCN;
        $qryBuilder = QueryBuilder::update($entityClass::tableName());

        if ($object instanceof QueryCondition) {

            $qryCondition = $object;
            $counter = 1;
            $qryBuilder->set($qryCondition->assignments());

            foreach ($qryCondition->conditions() as $field => $value) {

                if ($counter == 1) {
                    $qryBuilder->where($field, $value);
                }
                else {
                    // Hash conditions will always use the AND logical operator
                    $qryBuilder->and($field, $value);
                }
                ++$counter;
            }
        }
        elseif ($object instanceof EntityInterface) {
            $entity = $object;
        }

        $sql = $qryBuilder->build();
        return $this->dbAdapter->update($sql, $qryBuilder->dataBindings());
    }

    /**
     * Deletes a record/s by either its Primary Key or Composite Keys
     * @param int/array $params
     * @return int                  Number of records affected
     */
    public function delete(array $params): int {

        $entityClass = $this->entityFQCN;
        $qryBuilder = QueryBuilder::delete($entityClass::tableName());
        $counter = 1;

        foreach ($params as $field => $value) {

            if ($counter == 1) {
                $qryBuilder->where($field, $value);
            }
            else {
                $qryBuilder->and($field, $value);
            }
            ++$counter;
        }

        $sql = $qryBuilder->build();
        return $this->dbAdapter->delete($sql, $qryBuilder->dataBindings());
    }

    /**
     * Checks if an object is a new record by scrutinizing the id attribute
     * @param EntityInterface|QueryCondition|array $object
     * @return bool
     */
    private function isNewRecord($object): bool {

        if ($object instanceof QueryCondition) {
            return !$this->array_key_isset("id", $object->conditions());
        }
        elseif ($object instanceof EntityInterface) {
            return is_null($object->id());
        }
        elseif (is_array($object)) {
            return !$this->array_key_isset("id", $object);
        }

        return false;
    }

    /**
     * destroys this instance
     */
    public function __destruct() {
        $this->dbAdapter->disconnect();
        //$this->queryBuilder->__destruct();
    }
}