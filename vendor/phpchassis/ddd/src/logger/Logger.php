<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 17:15
 */
declare(strict_types=1);
namespace phpchassis\logger;

use phpchassis\logger\base\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Logger: This class acts as a wrapper for one of the concrete classes of the LoggerInterface strategy
 * @package PhpChassis\logger
 */
class Logger {

    /**
     * @var LoggerInterface $loggerInterface
     */
    private static $loggerInterface;

    /**
     * Returns a singleton instance of one of LoggerInterface's strategy classes
     * @return LoggerInterface
     */
    private static function loggerInterface(): LoggerInterface {

        if (self::$loggerInterface == null) {
            self::$loggerInterface = LoggerFactory::create();
        }
        return self::$loggerInterface;
    }

    public static function emergency($msg, array $context = array()) {
        self::loggerInterface()->emergency($msg, $context);
    }

    public static function alert($msg, array $context = array()) {
        self::loggerInterface()->alert($msg, $context);
    }

    public static function critical($msg, array $context = array()) {
        self::loggerInterface()->critical($msg, $context);
    }

    public static function notice($msg, array $context = array()) {
        self::loggerInterface()->notice($msg, $context);
    }

    public static function info($msg, array $context = array()) {
        self::loggerInterface()->info($msg, $context);
    }

    public static function debug($msg, array $context = array()) {
        self::loggerInterface()->debug($msg, $context);
    }

    public static function warning($msg, array $context = array()) {
        self::loggerInterface()->warning($msg, $context);
    }

    public static function error($msg, array $context = array()) {
        self::loggerInterface()->error($msg, $context);
    }
}