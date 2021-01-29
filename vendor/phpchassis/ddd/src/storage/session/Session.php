<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/4/2019
 * Time: 19:47
 */
namespace phpchassis\storage\session;

use phpchassis\storage\base\SessionFactory;
use phpchassis\storage\base\SessionInterface;
use phpchassis\lib\traits\PhpCommons;

/**
 * Class Session acts as a handler for the SessionInterface object
 *
 * @package phpchassis-ddd\storage\dto
 */
abstract class Session {

    use PhpCommons;

    /**
     * Holds a single instance of SessionInterface
     * @var SessionInterface $instance
     */
    private static $instance = null;

    /**
     * Returns a Singleton instance of SessionInterface
     * @return Session|static
     */
    public static function instance() : SessionInterface {

        if (self::$instance == null) {

            self::$instance = SessionFactory::create();
            self::$instance->open();
        }
        return self::$instance;
    }

    public function __destruct() {
        //self::$instance->close();
    }
}