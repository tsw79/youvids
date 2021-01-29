<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 01:55
 */
namespace youvids\application\dto;

use phpchassis\data\dto\PersistentDataInterface;

/**
 * Class LoginCredential
 *
 * @package YouVids\data\dto
 */
class LoginCredential implements PersistentDataInterface {

    public $username;
    public $password;

    /**
     * LoginCredential constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password) {

        $this->username = $username;
        $this->password = $password;
    }
}