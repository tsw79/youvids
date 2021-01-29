<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/20/2019
 * Time: 22:41
 */
namespace youvids\domain\repositories;

use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\repository\ {BaseRepository, RepositoryInterface};
use phpchassis\lib\collections\Collection;
use youvids\domain\entities\Subscriber;

/**
 * Class SubscriberRepo
 * @package youvids\data\repositories
 */
class SubscriberRepo extends BaseRepository implements RepositoryInterface {

    /**
     * SubscriberDataMapper constructor.
     * @param DatabaseAdapterInterface $dbAdapter
     */
    public function __construct(DatabaseAdapterInterface $dbAdapter) {

        $this->entityFQCN = Subscriber::fqcn();
        parent::__construct($dbAdapter);
    }

    /**
     * Count the number of times the user subscribed to another user's video
     * @param int $userToId
     * @return int
     */
    public function countBySubscribedTo(int $userToId): int {
        return $this->count(["user_to_id" => $userToId]);
    }

    public function addSubscription(int $userTo, int $userFrom): int {

        return $this->add([
            "user_to_id"    => $userTo,
            "user_from_id"  => $userFrom
        ]);
    }

    public function removeSubscription(int $userTo, int $userFrom): int {

        return $this->removeByComposites([
            "user_to_id"    => $userTo,
            "user_from_id"  => $userFrom
        ]);
    }

    public function findAllByFromUser(int $userFromId): Collection {

        return $this->findAll([
            "user_from_id" => $userFromId
        ]);
    }

    /**
     * Returns a list of Subscription Ids for a given User
     * @param int $userFromId
     * @param bool $asIndexedArray
     * @return array|bool
     */
    public function findAllSubscriptionIds(int $userFromId): ?array {

        $ids = $this->findAllByColumn("user_to_id", [
            "user_from_id" => $userFromId
        ]);

        return !empty($ids) ? array_values($ids) : null;
    }
}