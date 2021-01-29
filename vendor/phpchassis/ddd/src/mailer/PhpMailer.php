<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/2/2019
 * Time: 02:26
 */
namespace phpchassis\mailer;

/**
 * Class PhpMailer
 * @package phpchassis-ddd\mailer
 */
class PhpMailer implements MailerInterface {

    /**
     * Sends a message
     * @param MessageInterface $message
     * @return mixed
     */
    public function send(MessageInterface $message) {
      // TODO: Implement send() method.
    }
}