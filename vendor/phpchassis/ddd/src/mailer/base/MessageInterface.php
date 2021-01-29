<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/2/2019
 * Time: 01:51
 */
namespace phpchassis\mailer\base;

/**
 * Interface MessageInterface
 * @package phpchassis-ddd\mailer\base
 */
interface MessageInterface {

    /**
     * Returns the recipient
     * @return mixed
     */
    public function to();

    /**
     * Returns the sender
     * @return mixed
     */
    public function from();

    /**
     * Returns the subject
     * @return mixed
     */
    public function subject();

    /**
     * Returns the body
     * @return mixed
     */
    public function body();
}