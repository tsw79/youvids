<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/26/2019
 * Time: 04:24
 */
namespace phpchassis\data\service;

use phpchassis\data\dto\RequestDataInterface;

/**
 * Interface ApplicationServiceInterface
 *
 * @package phpchassis-ddd\data\services\dto
 */
interface ApplicationServiceInterface {

    /**
     * Executes the service
     * 
     * @param RequestDataInterface $requestData
     * @return mixed
     */
    public function execute(RequestDataInterface $requestData);
}