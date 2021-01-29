<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/14/2019
 * Time: 02:53
 */
namespace phpchassis\data;

use phpchassis\lib\exceptions\DIContainerException;
use Psr\Container\ {ContainerExceptionInterface, NotFoundExceptionInterface, ContainerInterface};

/**
 * Class DIContainer
 * @package phpchassis\psr
 */
class DIContainer implements ContainerInterface {

    /**
     * @var DIContainer
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $instancesContainer = array();

    /**
     * Returns a single instance of this class
     * @return DIContainer
     */
    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Registers different classes to the container
     * @param $abstractId
     * @param null $concrete
     * @return DIContainer
     */
    public function register($abstractId, $concrete = null): self {

        if (!$this->has($abstractId)) {
            if ($concrete == null) {
                $concrete = $abstractId;
            }
            $this->instancesContainer[$abstractId] = $concrete;
        }
        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param string $id
     * @param array|null $params
     * @return mixed
     * @throws DIContainerException
     * @throws \ReflectionException
     */
    public function get($id, array $params = null) {

        if (!$this->has($id)) {
            throw new DIContainerException("Identifier of the entry you're looking for hasn't been registered: \"{$id}\"");
        }
        if ($this->isConcreteClass($id)) {
            return $this->instancesContainer[$id];
        }
        return $this->autowire($id, $params);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has($id): bool {
        return (false!= isset($this->instancesContainer[$id]));
    }

    private function isConcreteClass($id): bool {
        return ($id === $this->instancesContainer[$id]) ? false : true;
    }

    /**
     * Autowiring dependancies - automatically handle dependancies for a specific class
     * @param $id
     * @param $params
     * @return mixed
     * @throws \ReflectionException
     */
    private function autowire($id, $params) {

        if ($id instanceof \Closure) {
            return $id($this, $params);
        }

        $reflector = new \ReflectionClass($id);

        if (!$reflector->isInstantiable()) {
            throw new DIContainerException("Class {$id} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (null === $constructor) {
            return $reflector->newInstance();
        }

        $dependancies = $this->dependancies(
            $constructor->getParameters()
        );

        $newInstance = $reflector->newInstanceArgs($dependancies);
        $this->instancesContainer[$id] = $newInstance;
        return $newInstance;
    }

    /**
     * Returns all dependancies for a particular class
     * @param array (\ReflectionParameter) $params
     * @return array
     */
    private function dependancies(array $params): array {

        $dependancies = array();

        foreach ($params as $param) {

            $dependancy = $param->getClass();
            if (null === $dependancy) {

                if (!$param->isDefaultValueAvailable()) {
                    throw new DIContainerException("Cannot resolve class dependancy for {$param->name}");
                }

                $dependancies[] = $param->getDefaultValue();
            }
            else {

                if (!$this->has($dependancy->name)) {
                    $this->register($dependancy->name);
                }
                $dependancies[] = $this->get($dependancy->name);
            }
        }

        return $dependancies;
    }

    /**
     * DIContainer constructor.
     *  Prevent creating multiple instances due to "private" constructor
     */
    private function __construct() {}

    /**
     * Prevent the instance from being cloned
     */
    private function __clone() {}

    /**
     * Prevent from being unserialized
     */
    private function __wakeup () {}
}