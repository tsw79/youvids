<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/5/2019
 * Time: 20:20
 */
namespace phpchassis\storage\cache;

use phpchassis\configs\base\ConfigLoader;
use Psr\SimpleCache\CacheInterface;

/**
 * Class CacheFactory
 * @package phpchassis\storage\cache
 */
class CacheFactory {

    /**
     * Creates a single instance of a CacheInterface adapter
     * @param string $adapter   Fully Qualified Class Name (FQCN)
     * @return CacheInterface
     */
    public static function create(string $adapter): CacheInterface {
        $config = ConfigLoader::cache();
        return new $adapter($config->dir);
    }
}