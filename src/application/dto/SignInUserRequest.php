<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/26/2019
 * Time: 05:37
 */
namespace youvids\application\dto;

use phpchassis\data\dto\ {RequestData, RequestDataInterface};

/**
 * Class SignInUserRequest
 *  SignInUser RequestData Date Transfer Object (DTO)
 *
 * @package YouVids\data\dto
 */
class SignInUserRequest extends RequestData implements RequestDataInterface {

    public $username;
    public $password;
    public $token;

    /**
     * SignInUserRequest constructor.
     * @param $username
     * @param $password
     * @param $token
     */
    public function __construct($username, $password, $token) {

        $this->username = $username;
        $this->password = $password;
        $this->token = $token;
    }

    /**
     * Returns the request as an array
     * @return array
     */
    public function toArray(): array {

        return [
            "username" => $this->username,
            "password" => $this->password,
            "token"    => $this->token
        ];
    }
}

