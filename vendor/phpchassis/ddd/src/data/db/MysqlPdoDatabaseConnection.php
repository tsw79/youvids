<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/22/2019
 * Time: 02:24
 */
namespace phpchassis\data\db;

use PDO;
use phpchassis\configs\base\ConfigLoader;

/**
 * Class MysqlPdoDatabaseConnection
 * @package PhpChassis\data\db
 */
class MysqlPdoDatabaseConnection {

    /**
     * Holds a single instance of PDO
     *
     * @var PDO
     */
    private $pdoConnection = null;

    /**
     * Database configuration settings
     *
     * @var object $dbConfig
     */
    private $dbConfig;

    /**
     * MysqlPdoDatabaseConnection constructor.
     */
    public function __construct() {
        $this->dbConfig = ConfigLoader::db();
    }

    /**
     * Close automatically the database connection when the instance of the class is destroyed
     */
    public function __destruct() {
        return $this->disconnect();
    }

    /**
     * Establish a connection
     */
    public function connect() : PDO {

        // If there is already a PDO object, return early
        if (null != $this->pdoConnection) {
            return $this->pdoConnection;
        }

        try {
            $this->pdoConnection = new PDO($this->dbConfig->dsn, $this->dbConfig->username, $this->dbConfig->password);
            $this->pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdoConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $this->pdoConnection;
        }
        catch(PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @return bool
     */
    public function disconnect() : bool {
        $this->connection = null;
        return true;
    }

    // Getter
//    public function connection() : PDO {
//        return $this->connection;
//    }
}