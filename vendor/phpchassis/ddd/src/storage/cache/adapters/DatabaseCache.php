<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/5/2019
 * Time: 03:42
 */
namespace phpchassis\storage\cache\adapters;

use Psr\SimpleCache\CacheInterface;
use phpchassis\storage\cache\Constants as CacheConstants;

/**
 * Class DatabaseCache
 * @package phpchassis\storage\cache\adapters
 */
class DatabaseCache implements CacheInterface {

    protected $sql;
    protected $connection;
    protected $table;
    protected $dataColumnName;
    protected $keyColumnName;
    protected $groupColumnName;
    protected $statementHasKey = NULL;
    protected $statementGetFromCache = NULL;
    protected $statementSaveToCache = NULL;
    protected $statementRemoveByKey = NULL;
    protected $statementRemoveByGroup= NULL;

    /**
     * Constructor
     */
    public function __construct($table, $idColumnName, $keyColumnName, $dataColumnName, $groupColumnName = CacheConstants::DEFAULT_GROUP) {

        $this->connection = $connection;
        $this->setTable($table);
        $this->setIdColumnName($idColumnName);
        $this->setDataColumnName($dataColumnName);
        $this->setKeyColumnName($keyColumnName);
        $this->setGroupColumnName($groupColumnName);
    }

    // -------------------- PREPARED STATEMENTS ------------------------------------------------------------------------

    public function prepareHasKey()
    {
        $sql = 'SELECT `' . $this->idColumnName . '` '
            . 'FROM `' . $this->table . '` '
            . 'WHERE `' . $this->keyColumnName . '` = :key ';
        $this->sql[__METHOD__] = $sql;
        $this->statementHasKey =
            $this->connection->pdo->prepare($sql);
    }

    public function prepareGetFromCache()
    {
        $sql = 'SELECT `' . $this->dataColumnName . '` '
            . 'FROM `' . $this->table . '` '
            . 'WHERE `' . $this->keyColumnName . '` = :key '
            . 'AND `' . $this->groupColumnName . '` = :group';
        $this->sql[__METHOD__] = $sql;
        $this->statementGetFromCache =
            $this->connection->pdo->prepare($sql);
    }
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Fetches a value from the cache.
     * @param string $key       The unique key of this item in the cache.
     * @param mixed $default    Default value to return if the key does not exist.
     * @return mixed            The value of the item from the cache, or $default in case of cache miss.
     * @throws \Psr\SimpleCache\InvalidArgumentException    @TODO MUST be thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null) {

        //$group = Constants::DEFAULT_GROUP;
        try {
            if (!$this->statementGetFromCache) {
                $this->prepareGetFromCache();
            }
            $this->statementGetFromCache->execute([
                'key' => $key,
                'group' => $group
            ]);
            while ($row = $this->statementGetFromCache->fetch(PDO::FETCH_ASSOC)) {
                if ($row && count($row)) {
                    yield unserialize($row[$this->dataColumnName]);
                }
            }
        }
        catch (Throwable $e) {
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new Exception(CacheConstants::ERROR_GET);
        }
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *  When writing to the cache, we first determine whether an entry for this cache key exists. 
     *    - If so, we perform an UPDATE; 
     *    - Otherwise, we perform an INSERT.
     *
     * @param string $key   The key of the item to store.
     * @param mixed $value  The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl Optional.
     *                      The TTL value of this item. If no value is sent and the driver supports TTL then the
     *                      library may set a default value for it or let the driver take care of that.
     * @return bool         True on success and false on failure.
     * @throws \Psr\SimpleCache\InvalidArgumentException    @TODO MUST be thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null) { /* $group = CacheConstants::DEFAULT_GROUP */

        $id = $this->hasKey($key);
        $result = 0;

        try {
            if ($id) {
                if (!$this->statementUpdateCache) {
                    $this->prepareUpdateCache();
                }
                $result = $this->statementUpdateCache->execute([
                    'key' => $key,
                    'data' => serialize($data),
                    'group' => $group,
                    'id' => $id
                ]);
            }
            else {
                if (!$this->statementSaveToCache) {
                    $this->prepareSaveToCache();
                }
                $result = $this->statementSaveToCache->execute([
                    'key' => $key,
                    'data' => serialize($data),
                    'group' => $group
                ]);
            }
        }
        catch (Throwable $e) {
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new Exception(CacheConstants::ERROR_SAVE);
        }
        return $result;
    }

    /**
     * Deletes an item from the cache by its unique key.
     *
     * @param string $key   The unique cache key of the item to delete.
     * @return bool         True if the item was successfully removed. False if there was an error.
     * @throws \Psr\SimpleCache\InvalidArgumentException    @TODO MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key) {

        $result = 0;

        try {
            if (!$this->statementRemoveByKey) {
                $this->prepareRemoveByKey();
            }
            $result = $this->statementRemoveByKey->execute(['key' => $key]);
        }
        catch (Throwable $e) {
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new Exception(CacheConstants::ERROR_REMOVE_KEY);
        }
        return $result;
    }

    /**
     * deleteByGroup
     */
    public function deleteByGroup($group) {

        $result = 0;
        try {
            if (!$this->statementRemoveByGroup) {
                $this->prepareRemoveByGroup();
            }
            $result = $this->statementRemoveByGroup->execute(['group' => $group]);
        }
        catch (Throwable $e) {
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new Exception(CacheConstants::ERROR_REMOVE_GROUP);
        }
        return $result;
    }

    /**
     * Wipes clean the entire cache's keys.
     * @return bool True on success and false on failure.
     */
    public function clear() {

        $uriString = '/?group=' . CacheConstants::DEFAULT_GROUP;
        $cacheRequest = new Request($uriString, 'get');
        $response = $this->deleteByGroup();
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys  A list of keys that can obtained in a single operation.
     * @param mixed $default  Default value to return for keys that do not exist.
     * @return iterable       A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *    MUST be thrown if $keys is neither an array nor a Traversable,
     *    or if any of the $keys are not a legal value.
     */
    public function getMultiple($keys, $default = null) {
        // TODO Implement getMultiple() method 
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable $values  A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl Optional. 
     *                          The TTL value of this item. If no value is sent and the driver supports TTL then the library 
     *                          may set a default value for it or let the driver take care of that.
     * @return bool             True on success; false on failure.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *    MUST be thrown if $values is neither an array nor a Traversable,
     *    or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys  A list of string-based keys to be deleted.
     * @return bool           True if the items were successfully removed. False if there was an error.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *    MUST be thrown if $keys is neither an array nor a Traversable,
     *    or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys)
    {
        // TODO: Implement deleteMultiple() method.
    }

    /**
     * Determines whether an item is present in the cache.
     * @param string $key The cache item key.
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException    // TODO MUST be thrown if the $key string is not a legal value.
     */
    public function has($key) {

        $result = 0;
        try {
            if (!$this->statementHasKey) {
                $this->prepareHasKey();
            }
            $this->statementHasKey->execute(['key' => $key]);
        }
        catch (Throwable $e) {
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new Exception(CacheConstants::ERROR_REMOVE_KEY);
        }
        return (int) $this->statementHasKey->fetch(PDO::FETCH_ASSOC)[$this->idColumnName];
    }

    public function setTable($name) {
        $this->table = $name;
    }
    
    public function getTable() {
        return $this->table;
    }
}