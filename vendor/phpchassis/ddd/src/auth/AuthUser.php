<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/17/2019
 * Time: 10:58
 */
namespace phpchassis\auth;

use phpchassis\auth\AccessControl;
use phpchassis\data\repository\RepositoryInterface;
use phpchassis\lib\exceptions\AuthenticationException;
use phpchassis\storage\session\Session;

/**
 * Class AuthUser
 * @package phpchassis\auth
 */
class AuthUser {

    /**
     * @var AuthUser
     */
    private static $instance;

    /**
     * @var RepositoryInterface
     */
    private $authUser = null;

    /**
     * Singleton instance os class AuthUser
     * @return AuthUser
     */
    public static function instance() : self {

        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Returns the User Entity that's logged in
     * @param RepositoryInterface|null $userRepo
     * @return \phpchassis\data\entity\EntityInterface|RepositoryInterface
     * @throws AuthenticationException
     */
    public function loggedInEntity(RepositoryInterface $userRepo = null) {

        if ($userRepo == null && $this->authUser == null) {
            throw new \InvalidArgumentException("You must pass the user repository as a paramater!");
        }

        if (!self::isLoggedIn()) {
            throw new AuthenticationException("User is not logged in!");
        }

        if ($this->authUser == null) {
            $authSession = self::sessionData();
            $this->authUser = $userRepo->findBy("username", $authSession->username);
        }

        return $this->authUser;
    }

    /**
     * Checks whether there's an Auth Session exists
     * @return bool
     */
    public static function isLoggedIn(): bool {
        return Session::instance()->has(AccessControl::SESSION_AUTH_KEY);
    }

    /**
     * Returns the authenticated (logged-in) user's session data
     * @return object
     */
    public static function sessionData(): object {
        return Session::instance()->get(AccessControl::SESSION_AUTH_KEY);
    }

    private function __construct() { }
}