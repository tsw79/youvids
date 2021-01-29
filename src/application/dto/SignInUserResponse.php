<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/26/2019
 * Time: 05:41
 */
namespace youvids\application\dto;

use youvids\domain\entities\User;
use phpchassis\data\dto\ {ResponseData, ResponseDataInterface};

/**
 * Class SignInUserResponse - Date Transfer Object (DTO)
 *
 * @package YouVids\data\dto
 */
class SignInUserResponse extends ResponseData implements ResponseDataInterface {

    public $id;
    public $username;

    public function __construct(User $user) {

        $this->id = $user->id();
        $this->username = $user->username();
    }
}

/*
With this approach, we can decouple the high-level policies from the low-level implementation details.
The communication between the delivery mechanism and the Domain is carried by data structures called DTOs.
 */

/*
The View is a layer that can both send and receive messages from the Model layer and/or
from the Controller layer. Its main purpose is to represent the Model to the user at the UI
level, as well as to refresh the representation in the UI each time the Model is updated.
Generally speaking, the View layer receives an object — often a Data Transfer
Object (DTO) instead of instances of the Model layer — thereby gathering all the needed
information to be successfully represented.
 */