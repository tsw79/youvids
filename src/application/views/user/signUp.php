<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/6/2019
 * Time: 22:06
 */
$response = (new \youvids\application\controllers\UserController())->signUp();

$content = <<<HTML
                <div class="message">{$response->flash()}</div>
                <form id="frmSignUp" action="{$response->request()->phpself}" method="POST">
                    <input type="text" name="firstName" id="firstName" minlength="2" placeholder="First Name" value="{$response->firstName->get()}" autocomplete="off" required>
                    <span class="error">{$response->firstName->error()}</span>
                    <input type="text" name="lastName" id="lastName" minlength="2" placeholder="Last Name" value="{$response->lastName->get()}" autocomplete="off" required>
                    <span class="error">{$response->lastName->error()}</span>
                    <input type="text" name="username" id="username" minlength="4" placeholder="Username" value="{$response->username->get()}" autocomplete="off" required>
                    <span class="error">{$response->username->error()}</span>
                    <input type="email" name="email" id="email" placeholder="Email" value="{$response->email->get()}" autocomplete="off" required>
                    <span class="error">{$response->email->error()}</span>
                    <input type="email" name="email2" id="email2" placeholder="Confirm email" value="{$response->email2->get()}" autocomplete="off" required>
                    <span class="error">{$response->email2->error()}</span>
                    <input type="password" name="password" id="password" placeholder="Password" value="{$response->password->get()}" autocomplete="off" required>
                    <span class="error">{$response->password->error()}</span>
                    <input type="password" name="password2" id="password2" placeholder="Confirm password" value="{$response->password2->get()}" autocomplete="off" required>
                    <span class="error">{$response->password2->error()}</span>
                    <input type="submit" name="submit" value="SUBMIT">
                </form>
                
                <script src="{$response->webroot()}/js/jquery-plugins/jquery-validation/v1.19.1/jquery.validate.min.js"></script>
                <script>
                    $().ready(function() {
		                
		                // validate signUp form on keyup and submit
		                $("#frmSignUp").validate({
                            rules: {
                                firstName: {
                                    required: true,
                                    minlength: 2
                                },
                                lastName: {
                                    required: true,
                                    minlength: 2
                                },
                                username: {
                                    required: true,
                                    minlength: 4,
                                    maxlength: 10
                                },
                                email: {
                                    required: true,
                                    email: true
                                },
                                email2: {
                                    required: true,
                                    email: true,
                                    equalTo: "#email"
                                },
                                password: {
                                    required: true,
                                    minlength: 5,
                                    maxlength: 15
                                },
                                password2: {
                                    required: true,
                                    minlength: 5,
                                    maxlength: 15,
                                    equalTo: "#password"
                                }
                            },
                            messages: {
                                firstName: {
                                    required: "Please enter your first name",
                                    minlength: "Your first name must consist of at least 2 characters"
                                },
                                lastName: {
                                    required: "Please enter your last name",
                                    minlength: "Your last name must consist of at least 2 characters"
                                },
                                username: {
                                    required: "Please enter a username",
                                    minlength: "Your username must consist of at least 4 characters",
                                    maxlength: "Your username must not exceed 10 characters"
                                },
                                email: "Please enter a valid email address",
                                email2: {
                                    required: "Please provide an email",
                                    equalTo: "Please enter the same email as above"
                                },
                                password: {
                                    required: "Please provide a password",
                                    minlength: "Your password must be at least 5 characters long",
                                    maxlength: "Your password must not me more than 15 characters"
                                },
                                password2: {
                                    required: "Please provide a password",
                                    minlength: "Your password must be at least 5 characters long",
                                    maxlength: "Your password must not me more than 15 characters",
                                    equalTo: "Please enter the same password as above"
                                }
                            }
		                });
                    });
                </script>
                
            </div>
            <a class="signInMsg" href="signIn.php">Already have an account? Sign in here.</a>
        </div>
    </div>
HTML;

$title = "Sign Up";
require_once(ROOT_DIR . "/src/application/views/layouts/sign_layout.inc.php");