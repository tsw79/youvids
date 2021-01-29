<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/16/2019
 * Time: 17:10
 */
namespace phpchassis\data\db;

use PDO;
use PDOStatement;
use phpchassis\data\db\base\ {DatabaseAdapterInterface, BaseDatabaseAdapter};
use phpchassis\data\db\MysqlPdoDatabaseConnection;

/*
    bindParam (use bindParam to bind the variable)
    ----------------------------------------------
    $sex = 'male';
    $s = $dbh->prepare('SELECT name FROM students WHERE sex = :sex');
    $s->bindParam(':sex', $sex); // use bindParam to bind the variable
    $sex = 'female';
    $s->execute(); // executed with WHERE sex = 'female'

    bindValue (use bindValue to bind the variable's value)
    ------------------------------------------------------
    $sex = 'male';
    $s = $dbh->prepare('SELECT name FROM students WHERE sex = :sex');
    $s->bindValue(':sex', $sex); // use bindValue to bind the variable's value
    $sex = 'female';
    $s->execute(); // executed with WHERE sex = 'male'
*/

/**
 * Class MysqlPdoDatabaseAdapter
 *  This class takes care of the statement part of the database adapter
 *
 * @package PhpChassis\data\db
 */
class MysqlPdoDatabaseAdapter extends BaseDatabaseAdapter implements DatabaseAdapterInterface {

    /**
     * @var PDOStatement
     */
    private $statement;

    /**
     * @var int
     */
    private $fetchMode = PDO::FETCH_CLASS;

    /**
     * MysqlPdoDatabaseAdapter constructor.
     */
    public  function __construct() {

        $mysqlPdoDbConn = new MysqlPdoDatabaseConnection();
        $this->connection = $mysqlPdoDbConn->connect();
        parent::__construct();
    }

    /**
     * Wrapper for the database connection's __destruct function
     */
    public function __destruct() {
//        return $this->connection->__destruct();
    }

    /**
     * Wrapper for the database connection's disconnect function
     */
    public function disconnect() {
//        return $this->connection->disconnect();
    }

    /**
     * fetch
     */
    public function fetch(string $sql, array $bindings = [], int $fetchMode = PDO::FETCH_ASSOC, $fetchArgument = null, array $fetchConstructorArguments = []) {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $statement->fetch($fetchMode, $fetchArgument);
    }

    /**
     * fetchAll
     */
    public function fetchAll(string $sql, array $bindings = [], int $fetchMode = PDO::FETCH_ASSOC, $fetchArgument = null, array $fetchConstructorArguments = []) {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $statement->fetchAll($fetchMode);
    }

    /**
     * insert
     */
    public function insert(string $sql, array $bindings = []): int {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $this->connection->lastInsertId();
    }

    /**
     * update
     */
    public function update(string $sql, array $bindings = []): int {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $statement->rowCount();
    }

    /**
     * delete
     */
    public function delete(string $sql, array $bindings = []): int {
        $statement = $this->connection->prepare($sql);
        $statement->execute($bindings);
        return $statement->rowCount();
    }
}