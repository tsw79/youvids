<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/2/2019
 * Time: 08:24
 */
namespace youvids\application\dto;

use phpchassis\data\dto\ {RequestData, RequestDataInterface};

/**
 * Class UserProfileRequest
 * @package youvids\domain\dto
 */
class UserProfileRequest extends RequestData implements RequestDataInterface {

    /**
     * @var string
     */
    public $username;

    /**
     * UserProfileRequest constructor.
     * @param $username
     */
    public function __construct($username) {
        $this->username = $username;
    }

    /**
     * Returns the request as an array
     * @return array
     */
    public function toArray(): array {

        return [
            "username" => $this->username
        ];
    }
}