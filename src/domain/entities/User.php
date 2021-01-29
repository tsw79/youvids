<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/9/2019
 * Time: 02:34
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\UserMapper;

/**
 * Class User entity
 * @package Youvids\data
 */
class User extends BaseEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "users";

    /**
     * @db-attribute
     * @var string
     */
    private $firstName;

    /**
     * @db-attribute
     * @var string
     */
    private $lastName;

    /**
     * @db-attribute
     * @var string
     */
    private $username;

    /**
     * @db-attribute
     * @var string
     */
    private $email;

    /**
     * @db-attribute
     * @var string
     */
    private $password;

    /**
     * @db-attribute
     * @var string
     */
    private $profilePic;

    /**
     * @db-attribute
     * @var int
     */
    private $accessStatus;

    /**
     * @db-attribute
     * @var int
     */
    private $accessLevel;

    /**
     * Returns true if this User has uploaded a given video
     * @param int $uploadedById
     * @return bool
     */
    public function hasUploadedVideo(int $uploadedById): bool {
        return ($this->id == $uploadedById);
    }

    /**
     * Getter/Setter for firstName
     * @param null $firstName
     * @return string
     */
    public function firstName($firstName = null) {

        if($firstName === null) {
            return $this->firstName;
        }
        else {
            $this->firstName = $firstName;
            return $this;
        }
    }

    /**
     * Getter/Setter for lastName
     * @param null $lastName
     * @return string
     */
    public function lastName($lastName = null) {

        if($lastName === null) {
            return $this->lastName;
        }
        else {
            $this->lastName = $lastName;
            return $this;
        }
    }

    /**
     * Returns the user's full name
     * @return string
     */
    public function fullName(): string {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Getter/Setter for username
     * @param null $username
     * @return string
     */
    public function username($username = null) {

        if($username === null) {
            return $this->username;
        }
        else {
            $this->username = $username;
            return $this;
        }
    }

    /**
     * Getter/Setter for email
     * @param null $email
     * @return string
     */
    public function email($email = null) {

        if($email === null) {
            return $this->email;
        }
        else {
            $this->email = $email;
            return $this;
        }
    }

    /**
     * @TODO Should password only have a Setter and no getter? ...verifyPassword() method instead?? A Getter would expose the password!
     * Getter/Setter for password
     * @param null $password
     * @return string
     */
    public function password($password = null) {

        if($password === null) {
            return $this->password;
        }
        else {
            $this->password = $password;
            return $this;
        }
    }

    /**
     * Getter/Setter for profilePic
     * @param null $profilePic
     * @return string
     */
    public function profilePic($profilePic = null) {

        if(null === $profilePic) {
            return $this->profilePic;
        }
        else {
            $this->profilePic = $profilePic;
            return $this;
        }
    }

    /**
     * Getter/Setter for $accessStatus
     * @param int
     * @return int
     */
    public function accessStatus($accessStatus = null) {

        if($accessStatus === null) {
            return $this->accessStatus;
        }
        else {
            $this->accessStatus = $accessStatus;
            return $this;
        }
    }

    /**
     * Getter/Setter for $accessLevel
     * @param int
     * @return int
     */
    public function accessLevel($accessLevel = null) {

        if($accessLevel === null) {
            return $this->accessLevel;
        }
        else {
            $this->accessLevel = $accessLevel;
            return $this;
        }
    }

    /**
     * Returns the profile pic of this user. Default pic if none.
     * @return string
     */
    public function getProfilePic(): string { 
        return (null == $this->profilePic) ? WEB_ROOT . "/images/profile_pics/default.png" : $this->profilePic;
    }

    /**
     * Returns the set default coverPhoto
     * @return string
     */
    public static function coverPhoto() {
        return WEB_ROOT . "/images/cover_photos/default_cover_photo.jpg";
    }

    /**
     * Returns the Fully Qualified Class Name
     * @return string
     */
    public static function fqcn(): string {
        return self::class;
    }

    /**
     * Returns the associated data mapper for a particular Entity
     * @return DataMapperInterface
     */
    public function dataMapper(): DataMapperInterface {

        if (null == $this->dataMapper) {
            $this->dataMapper = new UserMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}