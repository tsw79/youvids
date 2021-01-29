<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/19/2019
 * Time: 23:26
 */
namespace phpchassis\data\db\base;

use phpchassis\configs\base\ConfigLoader;
use phpchassis\data\db\base\DatabaseAdapterInterface;

/**
 * Class DatabaseAdapterFactory creates a particular Database Adapter
 *
 * @package 
 */
class DatabaseAdapterFactory {

    /**
     * Suffix for all database adapter concrete classes
     */
    const DB_ADAPTER_SUFFIX = "DatabaseAdapter";

    /**
     * Need to specify the fully qualified name as we're instantiating the object by using a string
     *  Ref:  https://www.php.net/manual/en/language.namespaces.rules.php
     */
    const DB_ADAPTER_FULLY_QUALIFIED_NAME = "\\phpchassis\\data\\db\\";

    /**
     * Database configuration settings
     *
     * @var object $dbConfig
     */
    private $dbConfig;

    /**
     * DatabaseAdapterProvider constructor.
     */
    public function __construct() {
        $this->dbConfig = ConfigLoader::db();
    }

    /**
     * Sets up, prepares and creates the database adapter
     */
    public function create() : DatabaseAdapterInterface {

        $dbTypeStr = ucfirst($this->dbConfig->type);            // e.g. $dbTypeStr = Mysql

        if(isset($this->dbConfig->extension) && $this->dbConfig->extension != null) {
            $dbTypeStr .= ucfirst($this->dbConfig->extension);  // e.g. $dbTypeStr = MysqlPdo
        }
                                                                // e.g. $dbTypeStr = MysqlPdoDatabaseAdapter
        $dbAdapterStr = self::DB_ADAPTER_FULLY_QUALIFIED_NAME . $dbTypeStr . self::DB_ADAPTER_SUFFIX;

        // TODO Should I make this a Singleton?
        //$dbAdapterStr::instance();                              // e.g. MysqlPdoDatabaseAdapter::instance()
        return new $dbAdapterStr();                             // e.g. new MysqlPdoDatabaseAdapter()
    }
}