<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/3/2019
 * Time: 13:57
 */
namespace youvids\application\controllers;

use phpchassis\http\FormRequest;

/**
 * Class UserRequest
 * @package youvids\application\controllers
 */
class UserRequest extends FormRequest {

    /**
     * List of fields that need to be validated per Controller's action
     * @override
     * @var array
     */
    protected $formFields = [
        "signIn" => ["username", "password"]
    ];

    /**
     * Authorization assignments
     * @return bool
     */
    public function authorization(): bool {
        return true;
    }

    /**
     * Returns a list of data (validation) rules
     * @return array|null
     */
    public function rules(): ?array {

        return [
            "firstName" => [
                [ 'key' => 'length', 'params' => ['min' => 2, 'max' => 20] ],
                [ 'key' => 'alnum', 'params' => ['allowWhiteSpace' => true] ],
                [ 'key' => 'required' ]
            ],
            "lastName"  => [
                [ 'key' => 'length', 'params' => ['min' => 2, 'max' => 20] ],
                [ 'key' => 'alnum', 'params' => ['allowWhiteSpace' => true] ],
                [ 'key' => 'required' ]
            ],
            "username"  => [
                [ 'key' => 'length', 'params' => ['min' => 4, 'max' => 10] ],
                [ 'key' => 'alnum', 'params' => ['allowWhiteSpace' => true] ],
                [ 'key' => 'required' ]
            ],
            "email"     => [
                [ 'key' => 'email', 'params' => [] ],
                [ 'key' => 'length', 'params' => ['max' => 25] ],
                [ 'key' => 'required' ]
            ],
            "password"  => [
                // @TODO Need to change class Validator to check for these changes!
                //[ 'key' => 'same_as', 'params' => ['input' => 'password2'], 'only' => 'signUp' ],
                [ 'key' => 'length', 'params' => ['min' => 5, 'max' => 15] ],
                [ 'key' => 'required' ]
            ]
        ];
    }

    /**
     * Returns a list of data filters
     * @return array|null
     */
    public function filters(): ?array {

        return [
            '*' => [
                [ 'key' => 'trim', "except" => ["password", "password2"] ],
                [ 'key' => 'strip_tags' ]
            ]
        ];
    }
}