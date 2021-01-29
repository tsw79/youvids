<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/27/2019
 * Time: 04:12
 */
namespace phpchassis\lib\captcha;

/**
 * Interface CaptchaInterface
 * @package phpchassis\lib\captcha
 */
interface CaptchaInterface {

    public function getLabel(): string;
    public function getImage();
    public function getPhrase();
}