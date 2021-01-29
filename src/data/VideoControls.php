<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/29/2019
 * Time: 07:58
 */
namespace youvids\data;

use youvids\data\entities\User;

/**
 * Class VideoControls
 * @package youvids\data
 */
trait VideoControls {

    /**
     * @var User    Logged-in user
     */
    protected $authUser;

    /**
     * Returns the User's profile button
     * @param User $user
     * @return string
     */
    public static function userProfileButton(string $username = null, string $profilePic = null): string {

        // @TODO Use the HetmlElement class to generate the anchor tag
        return "<a href='profile.php?username={$username}'>
                    <img src='{$profilePic}' class='profilePic'>
                </a>";
    }
}