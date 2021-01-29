<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 17:26
 */
namespace phpchassis\logger\base;

use phpchassis\configs\base\ConfigLoader;
use phpchassis\exceptions\LoggerException;
use Psr\Log\LoggerInterface;

/**
 * Class LoggerFactory
 * @package PhpChassis\logger\dto
 */
class LoggerFactory {

    /**
     * LOGGER_FQN = PLogger Fully Qualified Name
     *
     * Need to specify the fully qualified name as we're instantiating the object by using a string
     *  Ref:  https://www.php.net/manual/en/language.namespaces.rules.php
     */
    const LOGGER_FQN = "\\phpchassis-ddd\\logger\\";

    /**
     *
     */
    const LOGGER_CLASS_SUFFIX = "Log";

    /**
     * Database configuration settings for logger
     * @var object $logConfig
     */
    private static $logConfig;

    /**
     * Initializes and sets up the class before instantiating
     */
    private static function init() {
        self::$logConfig = ConfigLoader::log();
    }

    /**
     * create
     */
    public static function create(): LoggerInterface {

        try {
            self::init();
            $logType = self::$logConfig->type;
            $logClassName = self::LOGGER_FQN . ucfirst($logType) . self::LOGGER_CLASS_SUFFIX;
            return new $logClassName(self::$logConfig);
        }
        catch(LoggerException $e) {
            // TODO Print to log
            throw new \Exception("Logger not found: " . $e->getMessage());
        }
    }
}