<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 16:59
 */
namespace phpchassis\logger;

use Psr\Log\LoggerInterface;

/**
 * Class DbLog
 * @package phpchassis-ddd\logger
 */
class DbLog implements LoggerInterface {

    /**
     * DbLog constructor.
     * @param array $logConfig
     */
    public function __construct(Array $logConfig) {

        parent::__construct($logConfig);
        // TODO Set up the DB
    }

    /**
     * Writes a log to the db
     * @param $msg
     */
    protected function write($msg) {
        // TODO Write the log to the database
    }
}