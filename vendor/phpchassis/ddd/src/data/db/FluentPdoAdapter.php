<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/12/2019
 * Time: 21:37
 */
namespace phpchassis\data\db;

use Envms\FluentPDO\Query;
use phpchassis\data\db\base\DatabaseAdapterInterface;

/**
 * Class FluentPdoAdapter
 * @package phpchassis\data\db
 */
class FluentPdoAdapter implements DatabaseAdapterInterface {

    public const SELECT_ALL = true;
    public const CLEAR_SELECT_ALL = false;

    /**
     * @var MysqlPdoDatabaseConnection
     */
    // private $mysqlConnector;

    /**
     * @var PDO
     */
    private $pdoConnection;

    /**
     * @var \Envms\FluentPDO\Query
     */
    private $fpdoAdapter;

    /**
     * @var \Envms\FluentPDO\Queries
     */
    private $statement;

    /**
     * FluentPdoAdapter constructor.
     * @param MysqlPdoDatabaseConnection $mysqlConnector
     */
    public function __construct(MysqlPdoDatabaseConnection $mysqlConnector) {

        // $this->mysqlConnector = $mysqlConnector;
        $this->pdoConnection = $mysqlConnector->connect();
        $this->fpdoAdapter = new Query($this->pdoConnection);

        // Ensures that NULL values will be converted to SQL NULL Type, and NOT TRUNCATED!
        $this->fpdoAdapter->convertWriteTypes(true);

        // TODO For debugging purposes
        // Need to integrate this feature with DebugBar
        // $this->fpdoAdapter->debug = true;
    }

    /**
     * Builds raw sql query
     * @param string $sql
     * @param array|null $bindings
     * @return int
     */
    public function query(string $sql, array $bindings = null) {

        //$this->fpdoAdapter->getPdo()
        $this->statement = $this->pdoConnection->prepare($sql);
        $this->statement->execute($bindings);
        return $this->statement->rowCount();
    }

    /**
     * @param string $table
     * @param array|null $columns
     * @return FluentPdoAdapter
     * @throws \Envms\FluentPDO\Exception
     */
    public function load(string $table, array $columns = null, bool $selectAll = true): self {

        $this->statement = $this->fpdoAdapter->from($table);

        // Note:  By default, FluentPDO selects every column from a particular table, i.e. SELECT table_name.*
        // By passing NULL to select(), we're overriding the default behaviour to only return data from the given column/s.
        if (!$selectAll) {
            $this->statement->select(null);
        }
        if (null !== $columns) {
            $this->statement->select($columns);
        }
        return $this;
    }

    /**
     * @param string|null $column
     * @return mixed
     */
    public function one(string $column = null) { 
        return $this->statement->fetch($column);
    }

    /**
     * @return mixed
     */
    public function all(string $column = '') { 
        return $this->statement->fetchAll($column); 
    }

    private function insert(string $table, array $values) {

        return $this->fpdoAdapter->insertInto('article')
            ->values($values)
            ->execute();
    }

    /**
     * @param string $table
     * @param array $setData
     * @param array|null $conditions
     * @return int
     * @throws \Envms\FluentPDO\Exception
     */
    public function update(string $table, array $setData, array $conditions = null) {

        $this->statement = $this->fpdoAdapter->update($table)
            ->set($setData);

        if (null !== $conditions) {
            $this->statement->where($conditions);
        }

        return $this->statement->execute();
    }

    public function delete() {}

    // ---------------------- AGGREGATE FUNCTIONS ---------------------------------------------------

    /**
     * Returns the total number of values in the specified field
     * @param string $table
     * @param array $conditions
     * @return int
     * @throws \Envms\FluentPDO\Exception
     */
    public function count(string $table, array $conditions): int {
        return $this->execAggregateFunc("COUNT(*)", $table, $conditions);
    }

    /**
     * Returns the sum of all the values in the specified column
     * @param string $table
     * @param string $column
     * @param array $conditions
     * @return int
     */
    public function sum(string $table, string $column, array $conditions): int {
        return $this->execAggregateFunc("SUM({$column})", $table, $conditions);
    }

    /**
     * Returns the largest value from the specified table's field
     * @param string $table
     * @param string $column
     * @param array $conditions
     * @return int
     */
    public function avg(string $table, string $column, array $conditions): int {
        return $this->execAggregateFunc("AVG({$column})", $table, $conditions);
    }

    /**
     * Returns the largest value from the specified table's field
     * @param string $table
     * @param string $column
     * @param array $conditions
     * @return int
     * @throws \Envms\FluentPDO\Exception
     */
    public function max(string $table, string $column, array $conditions): int {
        return $this->execAggregateFunc("MAX({$column})", $table, $conditions);
    }

    /**
     * Returns the smallest value in the specified table field
     * @param string $table
     * @param string $column
     * @param array $conditions
     * @return int
     */
    public function min(string $table, string $column, array $conditions): int {
        return $this->execAggregateFunc("MIN({$column})", $table, $conditions);
    }

    /**
     * Aggregate function
     *  Performs  calculations on multiple rows of a single column of a table and returns a single value.
     * @param string $function
     * @param string $table
     * @param array $conditions
     * @return int
     * @throws \Envms\FluentPDO\Exception
     */
    private function execAggregateFunc(string $function, string $table, array $conditions): int {

        return $this->fpdoAdapter->from($table)
            ->select(null)
            ->select("{$function} AS value")
            ->where(null == $conditions ? false : $conditions)
            ->fetch("value");
    }
    // ----------------------------------------------------------------------------------------------

    /**
     * Add conditions to statement
     * @param array|null $conditions
     * @return FluentPdoAdapter
     */
    public function withConditions($conditions = false, array $params = []): self {

        $this->statement = $this->statement->where(
            $conditions,
            $params
        ); 
        return $this;
    }

    /**
     * Add clauses that come after the where clause
     * @param array|null $params
     * @return FluentPdoAdapter
     */
    public function withParams(?array $params): self {  

        if (null !== $params) { 

            if (isset($params["order"])) {  
                $this->statement->orderBy($params["order"]);
            }

            if (isset($params["limit"])) {
                $this->statement->limit($params["limit"]);
            }
        } 
        return $this;
    }

    public function fpdoAdapter(): Query {
        return $this->fpdoAdapter;
    }

    private function isNewRecord($object): bool {}
}