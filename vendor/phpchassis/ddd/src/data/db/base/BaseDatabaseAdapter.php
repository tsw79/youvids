<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/19/2019
 * Time: 19:41
 */
namespace phpchassis\data\db\base;

/**
 * Class BaseDatabaseAdapter
 */
abstract class BaseDatabaseAdapter {

    /**
     * @var DatabaseConnection $connection
     */
    protected $connection;

    public function __construct() {}

//    abstract public function connect();
//    abstract public function disconnect();
//    abstract public function __destruct();
//    abstract public function prepare($sql, array $options = array());
//    abstract public function execute(array $params = array());
//    abstract public function lastInsertId($name = null) : int;


}