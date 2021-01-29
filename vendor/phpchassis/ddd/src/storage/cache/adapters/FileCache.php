<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/5/2019
 * Time: 03:37
 */
namespace phpchassis\storage\cache\adapters;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Psr\SimpleCache\CacheInterface;
use phpchassis\storage\cache\Constants as CacheConstants;

/**
 * Class FileCache
 * @package phpchassis\storage\cache\adapters
 */
class FileCache implements CacheInterface {

    protected $dir;
    protected $prefix;
    protected $suffix;

    /**
     * Constructor
     */
    public function __construct($dir, $prefix = NULL, $suffix = NULL) {

        if (!file_exists($dir)) {
            error_log(__METHOD__ . ':' . CacheConstants::ERROR_DIR_NOT);
            throw new Exception(CacheConstants::ERROR_DIR_NOT);
        }
        $this->dir = $dir;
        $this->prefix = $prefix ?? CacheConstants::DEFAULT_PREFIX;
        $this->suffix = $suffix ?? CacheConstants::DEFAULT_SUFFIX;
    }

    /**
     * Fetches a value from the cache.
     * @param string $key    The unique key of this item in the cache.
     * @param mixed $default Default value to return if the key does not exist.
     * @return mixed         The value of the item from the cache, or $default in case of cache miss.
     * @throws \Psr\SimpleCache\InvalidArgumentException    @TODO MUST be thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null) { 
      
        //$group = Constants::DEFAULT_GROUP;

        $fn = $this->dir . '/'
            . $group . '/'
            . $this->prefix
            . md5($key)
            . $this->suffix;

        if (file_exists($fn)) {
            foreach (file($fn) as $line) {
                yield $line;
            }
        }
        else {
            return array();
        }
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key   The key of the item to store.
     * @param mixed $value  The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl Optional.
     *                      The TTL value of this item. If no value is sent and the driver supports TTL then the
     *                      library may set a default value for it or let the driver take care of that.
     * @return bool         True on success and false on failure.
     * @throws \Psr\SimpleCache\InvalidArgumentException    @TODO MUST be thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null) { 

        //$group = Constants::DEFAULT_GROUP;
        $baseDir = $this->dir . '/' . $group;

        if (!file_exists($baseDir)) {
            mkdir($baseDir);
        }
        $fn = $baseDir . '/'
            . $this->prefix
            . md5($key)
            . $this->suffix;
        return file_put_contents($fn, json_encode($value));
    }

    /**
     * Delete an item from the cache by its unique key.
     * @param string $key   The unique cache key of the item to delete.
     * @return bool         True if the item was successfully removed. False if there was an error.
     * @throws \Psr\SimpleCache\InvalidArgumentException    // TODO MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key) {

        $action = function ($name, $md5Key, &$item) {
            if (strpos($name, $md5Key) !== false) {
                unlink($name);
                $item++;
            }
        };
        return $this->findKey($key, $action);
    }

    /**
     * @param $group
     * @return int
     */
    public function removeByGroup($group) {

        $removed = 0;
        $baseDir = $this->dir . '/' . $group;

        $pattern = $baseDir . '/'
            . $this->prefix . '*'
            . $this->suffix;

        foreach (glob($pattern) as $file) {
            unlink($file);
            $removed++;
        }
        return $removed;
    }

    /**
     * Wipes clean the entire cache's keys.
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        // TODO: Implement clear() method.
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
    public function getMultiple($keys, $default = null)
    {
        // TODO: Implement getMultiple() method.
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable $values  A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl Optional. 
     *    The TTL value of this item. If no value is sent and the driver supports TTL, then the library 
     *    may set a default value for it or let the driver take care of that.
     * @return bool             True on success and false on failure.
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
     * @param string $key   The cache item key.
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException    // TODO MUST be thrown if the $key string is not a legal value.
     */
    public function has($key) {

        $action = function ($name, $md5Key, &$item) {
            if (strpos($name, $md5Key) !== false) {
                $item ++;
            }
        };
        return $this->findKey($key, $action);
    }

    /**
     * @param $key
     * @param callable $action
     * @return int
     */
    protected function findKey($key, callable $action) {

        $md5Key = md5($key);

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->dir),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $item = 0;
        foreach ($iterator as $name => $obj) {
            $action($name, $md5Key, $item);
        }
        return $item;
    }
}