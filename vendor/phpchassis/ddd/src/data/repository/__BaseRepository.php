<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/13/2019
 * Time: 02:16
 */

namespace phpchassis\data\repository;


class __BaseRepository
{
    /**
     * @var UserDataMapper $userMapper
     */
    protected $dataMapper;

    /**
     * @var Validator $validator
     */
    protected $validator;

    /**
     * AbstractRepository constructor.
     *
     * @param DataMapperInterface $dataMapper
     */
    public function __construct(DataMapperInterface $dataMapper) {
        $this->dataMapper = $dataMapper;
    }

    /**
     * Returns an EntityInterface loaded from the db
     * @param array $conditions
     * @return EntityInterface
     */
    public function get(array $conditions, array $params = null, array $columns = null): EntityInterface {
        return $this->dataMapper->loadOne(
            new QueryCondition($conditions)
        );
    }

    /**
     * Returns a record based on a name (field) and its value
     * @param string $name
     * @param $value
     * @return mixed
     */
    public function by(string $name, $value): EntityInterface {
        return $this->get([$name => $value]);
    }

    /**
     * Alias for method get
     * @param int $id
     * @return mixed
     */
    public function byId(int $id): EntityInterface {
        return $this->get(["id" => $id]);
    }

    /**
     * Alias for method get
     * @param array $params
     * @return mixed
     */
    public function one(array $params): EntityInterface {
        return $this->get($params);
    }

    public function column(string $column, array $conditions = null, array $params = null) {
        return $this->get($conditions, $params, array($column));
    }

    /**
     * @param array $conditions
     * @return Collection|null
     */
    public function all(array $conditions = null, array $params = null): ?Collection {
        return $this->dataMapper->loadAll(
            new QueryCondition($conditions, $params)
        );
    }

    /**
     * Adds a record to the data source
     * @param EntityInterface|array $object
     * @return int
     */
    public function add($object): int {
        return $this->dataMapper->save($object);
    }

    /**
     * Edits a record in the data source
     * @param EntityInterface|QueryCondition $object
     * @return int
     */
    public function edit($object): int {
        return $this->dataMapper->save($object);
    }

    /**
     * Count the number of records for a particular condition
     * @param array $conditions
     * @return int
     */
    public function countBy(array $conditions): int {
        return $this->dataMapper->count(
            new QueryCondition($conditions)
        );
    }

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

    /**
     * Returns true if the given conditions are met
     * @param array $conditions
     * @return bool
     */
    public function has(array $conditions): bool {
        return $this->dataMapper->count(
            new QueryCondition($conditions)
        );
    }
}