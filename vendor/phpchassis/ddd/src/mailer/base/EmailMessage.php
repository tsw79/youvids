<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/2/2019
 * Time: 01:55
 */
namespace phpchassis\mailer\base;

/**
 * Class EmailMessage
 * @package phpchassis-ddd\mailer\base
 */
class EmailMessage implements MessageInterface {

    /**
     * The recipient
     * @var string
     */
    private $to;

    /**
     * The sender
     * @var string
     */
    private $from;

    /**
     * The subject
     * @var $subject
     */
    private $subject;

    /**
     * The body
     * @var $body
     */
    private $body;

    /**
     * Constructor
     */
    public function __construct($to, $from, $subject, $body) {

        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Returns the recipient
     * @return mixed
     */
    public function to() {
        return $this->to;
    }

    /**
     * Returns the sender
     * @return mixed
     */
    public function from() {
        return $this->from;
    }

    /**
     * Returns the subject
     * @return mixed
     */
    public function subject() {
        return $this->subject;
    }

    /**
     * Returns the body
     * @return mixed
     */
    public function body() {
        return $this->body;
    }
}