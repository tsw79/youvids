<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/2/2019
 * Time: 02:32
 */
namespace phpchassis\mailer;

use phpchassis\mailer\base\MailerInterface;
use phpchassis\mailer\base\MessageInterface;

/**
 * Class SwiftMailer
 * @package phpchassis-ddd\mailer
 */
class SwiftMailer implements MailerInterface {

    /**
     * Sends a message
     * @param MessageInterface $message
     * @return mixed
     */
    public function send(MessageInterface $message) {
        // TODO: Implement send() method.
    }
}