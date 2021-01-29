<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/9/2019
 * Time: 02:32
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\ {AbstractEntity, EntityInterface};
use phpchassis\validator\rules\ {RequiredRule, EmailRule, SameAsRule, StringLenRule};

/**
 * Class BaseUser
 * @package Youvids\data\dto
 */
class BaseUser extends AbstractEntity implements EntityInterface {

    /**
     * @var string $tableName
     */
    protected static $tableName = "users";

    /**
     * Entity mapping
     * @var array $mapping
     */
    protected $mappings = [
        "first_name"        => "firstName",
        "last_name"         => "lastName",
        "username"          => "username",
        "email"             => "email",
        "password"          => "password",
        "profile_pic"       => "profilePic"
    ];

    /**
     * Sets rules for validation
     */
    public function rules(): array {

        // @TODO Where do I create the Validator instance???

        $this->validator
            ->addRule(
                (new RequiredRule("firstName"))
                    ->add("lastName")
                    ->add("username")
                    ->add("email")->add("email2")
                    ->add("password")->add("password2")
            )
            ->addRule(
                (new EmailRule("email"))->add("email2")
            )
            ->addRule(new SameAsRule("email", "email2"))
            ->addRule(new SameAsRule("password", "password2"))
            ->addRule(new StringLenRule("email", 5, 25));

        /*
        $this->_____validator
            ->addRule(
                new RequiredRule("firstName")->add("lastName")->add("username")->add("email"),
                'firstName'
            )
            ->addRule(new EmailValidator("email")->add("email2")
            ->addRule(new SameAsValidator("email", "email2"))
            ->addRule(
                new CreditCardValidator("creditCard"),
                "when" => function($model) {
                    return $model->cardType === 'creditcard';
                }
            );
        */
    }
}