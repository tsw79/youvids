<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/16/2019
 * Time: 17:17
 */
namespace phpchassis\data\db;

use phpchassis\data\db\base\ {BaseDatabaseAdapter, IDatabaseAdapter};

/**
 * Class SqlServerDatabaseConnection
 */
class SqlServerDatabaseAdapter extends BaseDatabaseAdapter implements IDatabaseAdapter {

    public function connect() : PDO {

        try {
            $con = null;
            //$con = new PDO("sqlsrv:Server=$server_name;Database=$database_name;ConnectionPooling=0", "user_name", "password");
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            return $con;
        }
        catch(PDOException $e) {
            echo "Connection to database failed: " . $e->getMessage();
        }
    }

    public function disconnect() : bool {

    }

    public function __destruct() {
        return $this->disconnect();
    }

    public function query($qry) {
        //DatabaseConnector::instance();
    }

    public function select() {
      // TODO: Implement select() method.
    }

    public function insert(Model $model) {
        var_dump($model);exit;
    }

    public function update() {
      // TODO: Implement update() method.
    }

    public function delete() {
      // TODO: Implement delete() method.
    }

    public function fetch() {
      // TODO: Implement fetch() method.
    }

    public function insertId() {
      // TODO: Implement insertId() method.
    }

    public function prepare($sql, array $options = array()) {
        // TODO: Implement prepare() method.
    }

    public function execute(array $params = array()) {
        // TODO: Implement execute() method.
    }

    public function lastInsertId($name = null) : int {
        // TODO: Implement lastInsertId() method.
    }

    public function fetchAll($fetchStyle = null, $column = 0) {
        // TODO: Implement fetchAll() method.
    }
}