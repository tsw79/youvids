<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/6/2019
 * Time: 19:27
 */
$response = (new \youvids\application\controllers\UserController())->signIn();

$content = <<<HTML
                <div class="message">{$response->flash()}</div>
                <form id="frmSignIn" action="{$response->request()->phpself}" method="POST">
                    <input type="text" name="username" placeholder="Username" value="{$response->username->get()}" autocomplete="off" required>
                    <span class="error">{$response->username->error()}</span>
                    <input type="password" name="password" placeholder="Password" autocomplete="off" required>
                    <span class="error">{$response->password->error()}</span>
                    <input type="submit" name="submit" value="SUBMIT">
                    <input type="hidden" name="token" value="{$response->request()->token}">
                </form>
                
                <script src="{$response->webroot()}/js/jquery-plugins/jquery-validation/v1.19.1/jquery.validate.min.js"></script>
                <script>
                    $().ready(function() {
		                
		                // validate signIn form on keyup and submit
		                $("#frmSignIn").validate({
                            rules: {
                                username: {
                                    required: true,
                                    minlength: 4,
                                    maxlength: 10
                                },
                                password: {
                                    required: true,
                                    minlength: 5,   /* @TODO Need to change this to 6 */
                                    maxlength: 15
                                }
                            },
                            messages: {
                                username: {
                                    required: "Please enter a username",
                                    minlength: "Your username must consist of at least 4 characters",
                                    maxlength: "Your username must not exceed 10 characters"
                                },
                                password: {
                                    required: "Please provide a password",
                                    minlength: "Your password must be at least 6 characters long",
                                    maxlength: "Your password must not me more than 15 characters"
                                }
                            }
		                });
                    });
                </script>
                
            </div>
            <a class="signInMsg" href="signUp.php">Don't have an account? Sign up here.</a>
        </div>
    </div>
HTML;

$title = "Sign In";
require_once(ROOT_DIR . "/src/application/views/layouts/sign_layout.inc.php");
?>