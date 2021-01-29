<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/23/2019
 * Time: 21:12
 */
namespace youvids\domain\entities;

/**
 * Class UserCollection
 *
 * @package YouVids\data\entities
 */
class UserCollection {

    private $users = array();

    public function push(User $user) {

        $this->users = $user;
        return $this;
    }

    public function all() {
        return $this->users;
    }

    public function iterator() {
        return new \ArrayIterator($this->users);
    }
}