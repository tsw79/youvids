<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/26/2019
 * Time: 04:22
 */
namespace phpchassis\data\service;

use phpchassis\data\repository\RepositoryInterface;

/**
 * Class AbstractApplicationService
 *
 *      Note:  Application Services Operate on scalar types, transforming them into Domain types. A scalar type can be
 *      considered any type that's unknown to the Domain Model. This includes primitive types and types that don't
 *      belong to the Domain.
 *
 * @package phpchassis-ddd\data\service;
 */
abstract class ApplicationService {

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var
     */
    public $params = null;

    /**
     * ApplicationService constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Add additional data by passing paramaters here
     * @param array $params
     * @return $this
     */
    public function withParams(array $params) {
        $this->params = $params;
        return $this;
    }

    /**
     * Type cast the params attribute from an array to an stdClass object
     * @return ApplicationService
     */
    public function toObject(): self {
        $this->params = (object) $this->params;
        return $this;
    }

    /**
     * Use this magic method to return the value that was set by the withParams method.
     * __get magic implementation for client code to read as: $clientObj->property
     *
     * @param $propertyName
     * @return mixed
     */
    public function __get($propertyName) {
        if (is_array($this->params)) {
            return $this->params[$propertyName];
        }
        else {
            return $this->params->$propertyName;
        }
    }
}