<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/2/2019
 * Time: 22:46
 */
list(
    $firstName,
    $lastName,
    $email,
    $flashMessage
) = (new \youvids\application\controllers\AccountController())->settings();

$content = <<<HTML
    <div class="settingsContainer column">
        <div class="formSection">
            <div class="message">
                {$flashMessage}
            </div>
            <form action='settings.php' method='POST' enctype='multipart/form-data'>
                <span class='title'>User details</span>
                <div class='form-group'>
                    <input class='form-control' type='text' placeholder='First name' name='firstName' value='{$firstName}' required>
                </div>
                <div class='form-group'>
                    <input class='form-control' type='text' placeholder='Last name' name='lastName' value='{$lastName}' required>
                </div>
                <div class='form-group'>
                    <input class='form-control' type='email' placeholder='Email' name='email' value='{$email}' required>
                </div>
                <button type='submit' class='btn btn-primary' name='btnDetails'>Save</button>
            </form>
        </div>
    
        <div class="formSection">
            <div class="message">
                {$flashMessage}
            </div>
            <form action='settings.php' method='POST' enctype='multipart/form-data'>
                <span class='title'>Update password</span>
                <div class='form-group'>
                    <input class='form-control' type='password' placeholder='Old password' name='oldPassword' required>
                </div>
                <div class='form-group'>
                    <input class='form-control' type='password' placeholder='New password' name='newPassword' required>
                </div>
                <div class='form-group'>
                    <input class='form-control' type='password' placeholder='Confirm new password' name='newPassword2' required>
                </div>
                <button type='submit' class='btn btn-primary' name='btnPassword'>Save</button>
            </form>
        </div>
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");