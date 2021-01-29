<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 18:19
 */
namespace phpchassis\logger;

use Psr\Log\LoggerInterface;

/**
 * Class PhpLog
 * @package phpchassis-ddd\logger
 */
class PhpLog implements LoggerInterface {

    /**
     * PhpLog constructor.
     * @param array $logConfig
     */
    public function __construct(array $logConfig) {
        parent::__construct($logConfig);
        // TODO Set up the Php log
    }

    /**
     * Writes a log to the db
     * @param $msg
     */
    protected function write($msg) {
        // Write the log to the php file
        error_log("Invalid input on user login", 3, "/var/www/example.com/log/error.log");
    }
}