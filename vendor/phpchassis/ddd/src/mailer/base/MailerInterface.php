<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/2/2019
 * Time: 02:01
 */
namespace phpchassis\mailer\base;

/**
 * Interface MailerInterface
 * @package phpchassis-ddd\mailer\base
 */
interface MailerInterface {

    /**
     * Sends a message
     * @param MessageInterface $message
     * @return mixed
     */
    public function send(MessageInterface $message);
}