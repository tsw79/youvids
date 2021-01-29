<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 06:05
 */
namespace phpchassis\filter;

/**
 * Interface CallbackInterface
 * @package phpchassis\filter
 */
interface CallbackInterface {

    public function __invoke ($item, array $params = []): Result;
}