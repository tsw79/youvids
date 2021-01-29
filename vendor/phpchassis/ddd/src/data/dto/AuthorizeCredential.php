<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 01:55
 */
namespace phpchassis\data\dto;

use phpchassis\data\dto\PersistentDataInterface;

/**
 * Class AuthorizeCredential
 * @package youvids\application\dto
 */
class AuthorizeCredential implements PersistentDataInterface {

    public $accessStatus;
    public $accessLevel;

    /**
     * AuthorizeCredential constructor.
     * @param $accessStatus
     * @param $accessPassword
     */
    private function __construct($accessStatus, $accessLevel) {

        $this->accessStatus = $accessStatus;
        $this->accessLevel = $accessLevel;
    }

    public static function create($accessStatus, $accessLevel): self {
        return new self($accessStatus, $accessLevel);
    }
}