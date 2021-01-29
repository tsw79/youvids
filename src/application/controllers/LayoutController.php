<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/17/2019
 * Time: 00:57
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\data\db\base\DatabaseAdapterInterface;
use phpchassis\data\db\MysqlPdoDatabaseConnection;
use phpchassis\data\DIContainer;
use youvids\domain\repositories\ {SubscriberRepo, UserRepo};
use phpchassis\data\db\FluentPdoAdapter;

/**
 * Class LayoutController
 * @package youvids\controllers
 */
class LayoutController {

    /**
     * @var UserRepo
     */
    private $userRepo;

    /**
     * LayoutController constructor.
     */
    public function __construct() {

        //$this->dbAdapter = (new DatabaseAdapterFactory())->create();
        $dbAdapter = new FluentPdoAdapter(
            new MysqlPdoDatabaseConnection()
        );

        DIContainer::instance()
            ->register(DatabaseAdapterInterface::class, $dbAdapter)
            ->register(UserRepo::class)
            ->register(SubscriberRepo::class);

        $this->userRepo = DIContainer::instance()->get(UserRepo::class);
    }

    /**
     * main action
     * @return array
     */
    public function main(): array {

        return [
            $this->userProfileNav(),
            $this->navAuthList()
        ];
    }



    // TODO Move this to a different layer
    private function userProfileNav(): string {

        if (AuthUser::isLoggedIn()) {

            $loggedInUser = AuthUser::instance()->loggedInEntity($this->userRepo);
            $profilePic = $loggedInUser->getProfilePic();
            $profileLink = "/src/application/views/user/profile.php?username={$loggedInUser->username()}";

            return "<a href='$profileLink' class='userProfilePic'>
                      <img src='$profilePic' class='profilePic'>
                    </a>";
        }
        else {
            $signInLink = "/src/application/views/user/signIn.php";
            return "<a href='{$signInLink}'>
                        <span class='signInLink'>SIGN IN</span>
                    </a>";
        }
    }

    // @TODO Move this to a different layer
    private function navAuthList(): string {

        $navListItem = '';

        if (AuthUser::isLoggedIn()) {

            $navListItem .= $this->navListItem(
                "Settings",
                "settings",
                "/src/application/views/account/settings.php"
            );
            $navListItem .= $this->navListItem(
                "Log Out",
                "log-out",
                "/src/application/views/user/signOut.php"
            );
            $navListItem .= $this->navSubscriptions();
        }

        return $navListItem;
    }

    // @TODO Move this to a different layer
    private function navListItem(string $text, string $icon, string $link): string {

        return "<div class='navigationItem'>
                    <a href='$link'>
                        <i data-feather='$icon' stroke-width='1.2' color='#000' width='20px'></i>
                        <span class='label'>$text</span>
                    </a>
                </div>";
    }

    // @TODO Move this to a different layer
    private function navSubscriptions() {

        $loggedInUser = AuthUser::instance()->loggedInEntity($this->userRepo);
        $subscriberRepo = DIContainer::instance()->get(SubscriberRepo::class);

        $this->userRepo
            ->withAssocRepo("subscriber", $subscriberRepo)
            ->toObject();

        $subscriptions = $this->userRepo->findSubscriptions($loggedInUser->id());

        $html = "<span class='heading'>Subscriptions</span>";

        foreach($subscriptions as $user) {

            $html .= $this->navListItem(
                $user->username(),
                $user->profilePic(),
                APP_ROOT . "/src/application/views/user/profile.php?username={$user->username()}"
            );
        }

        return $html;
    }
}