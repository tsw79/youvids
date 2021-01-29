<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 18:24
 */
namespace phpchassis\logger;

use Psr\Log\LoggerInterface;

/**
 * Class JsonLog
 * @package phpchassis-ddd\logger
 */
class JsonLog implements LoggerInterface {

    /**
     * JsonLog constructor.
     * @param array $logConfig
     */
    public function __construct(Array $logConfig) {
        parent::__construct($logConfig);
        // TODO Set up Json
    }

    /**
     * Writes a log to Json
     * @param $msg
     */
    protected function write($msg) {
        // TODO Write the log to the JSON
    }
}