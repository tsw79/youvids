<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/23/2019
 * Time: 19:56
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\lib\collections\Collection;
use youvids\domain\entities\User;
use phpchassis\data\db\query\QueryCondition;
use phpchassis\data\repository\ {AssociateRepository, RepositoryInterface};

/**
 * Class UserRepo
 *
 * @package YouVids\data\repositories
 */
class UserRepo extends AssociateRepository implements RepositoryInterface {

    /**
     * UserDataMapper constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = User::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Returns a USer based on a given username
     *
     * @param string $username
     * @return User/bool
     */
    public function findByUsername(string $username) {
        return $this->findBy("username", $username);
    }

    /**
     * Returns a USer based on a given username
     *
     * @param $email
     * @return User/bool
     */
    public function findByEmail($email) {
        return $this->findBy("email", $email);
    }

    /**
     * Returns true if a given email already exists
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool {
        return $this->countBy(["email" => $email]) > 0;

    }

    /**
     * Returns true if the password belongs to the given user
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $username, string $password): bool {

        $count = $this->countBy([
            "username" => $username,
            "password" => $password
        ]);
        return $count > 0;
    }

    /**
     * Returns true if the email belongs to the given user
     * @param string $username
     * @param string $email
     * @return bool
     */
    public function verifyEmail(string $username, string $email): bool {

        $count = $this->count([
            "username" => $username,
            "email"    => $email
        ]);
        return $count > 0;
    }

    /**
     * Adds a new User entity
     * @param EntityInterface|QueryCondition|array $object
     * @return int
     * @throws \Exception
     */
    public function add($object): int {

        if (!$object instanceof User) {
            throw new \Exception("Object must be of type User.");
        }

        return $this->dataMapper->newUser($object);
    }

    // ************************************************************************************************************
    /**
     * Initialiases an User entity before inserting it to the User table
     * @param EntityInterface $entity
     * @return bool|EntityInterface
     */
    public function newUser(EntityInterface $entity): int {

        // @TODO Is this the best place to secure and generate the hash for the password??
        // Secure and set the password
        $entity->password(
            PasswordWrapper::instance()->generate($entity->password())
        );

        //@TODO Need to do this earlier, right after validation has succeded
        unset($_POST['password1']);
        unset($_POST['password2']);

        return parent::save($entity);
    }
    // ***************************************************************************************************************

    /**
     * Returns true if the given user is subscribed to another user
     * @param int $userToId
     * @param int $userFromId
     * @return bool
     */
    public function isSubscribed(int $userToId, int $userFromId): bool {

        return $this->associateRepos->subscriber->count([
            "user_to_id"   => $userToId,
            "user_from_id" => $userFromId
        ]);
    }

    /**
     * Returns the number of subscribers for a given user
     * @param $userToId
     * @return int
     */
    public function countSubscribers($userToId): int {
        return $this->associateRepos->subscriber->countBySubscribedTo($userToId);
    }

    /**
     * Subscribes a given user to another user
     * @param int $userTo
     * @param int $userFrom
     * @return mixed
     */
    public function subscribe(int $userTo, int $userFrom) {
        return $this->associateRepos->subscriber->addSubscription($userTo, $userFrom);
    }

    /**
     * Unsubscribes a given user from another user
     * @param int $userTo
     * @param int $userFrom
     * @return mixed
     */
    public function unsubscribe(int $userTo, int $userFrom) {
        return $this->associateRepos->subscriber->removeSubscription($userTo, $userFrom);
    }

    /**
     * Returns a Collection of Users who've subscribed to the given user
     * @param int $userFromId
     * @return Collection
     */
    public function findSubscriptions(int $userFromId): ?Collection {

        $subscribers = $this->associateRepos->subscriber->findAllByFromUser($userFromId);
        $collection = null;

        if (null != $subscribers) {
            $collection = new Collection();
            foreach ($subscribers as $subscriber) {
                $collection->set(
                    $this->findById($subscriber->userToId())    // @TODO If I loop 100 times, I will make a 100 requests to the DB!! Need to optimize this as above recommendation.
                );
            }
        }

        return $collection;
    }

    /**
     * Update the details for a given user
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return int
     */
    public function editUserDetails(int $id, string $firstName, string $lastName, string $email): int {

        $setData = [
            "first_name" => $firstName,
            "last_name"  => $lastName,
            "email"      => $email
        ];
        $condition = ["id" => $id];

        return $this->edit($setData, $condition);
    }

    /**
     * Edits the password of a given user
     * @param int $id   User Id
     * @param string $newPassword
     * @return mixed
     */
    public function editPassword(int $id, string $newPassword): int {
        return $this->edit(["password" => $newPassword], ["id" => $id]);
    }
}