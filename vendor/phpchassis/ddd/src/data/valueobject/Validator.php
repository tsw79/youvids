<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/29/2019
 * Time: 02:13
 */
namespace phpchassis\data\valueobject;

/**
 * Class Validator
 * @package phpchassis-ddd\data\valueobject
 */
abstract class Validator {

    /**
     * @var ValidationHandler
     */
    private $validationHandler;

    /**
     * Validator constructor.
     * @param ValidationHandler $validationHandler
     */
    public function __construct(ValidationHandler $validationHandler) {
        $this->validationHandler = $validationHandler;
    }

    protected function handleError($error) {
        $this->validationHandler->handleError($error);
    }

    abstract public function validate();
}