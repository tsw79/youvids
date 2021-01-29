<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/5/2019
 * Time: 01:25
 */
namespace phpchassis\storage\base;

use phpchassis\configs\base\ConfigLoader;
use phpchassis\storage\base\SessionInterface;
use phpchassis\exceptions\SessionException;

/**
 * Class SessionFactory
 * @package phpchassis-ddd\storage\session\dto
 */
class SessionFactory {

    /**
     * SESSION_CLASS_FQN = Session Fully Qualified Name
     * Need to specify the fully qualified name as we're instantiating the object by using a string
     *  Ref:  https://www.php.net/manual/en/language.namespaces.rules.php
     */
    const SESSION_CLASS_FQN = "\\phpchassis\\storage\\session\\";

    /**
     * Suffix for all Session class names
     */
    const SESSION_CLASS_SUFFIX = "Session";

    /**
     * Database configuration settings
     *
     * @var object $dbConfig
     */
    private static $storageConfig;

    /**
     * Initialise
     */
    private static function init() {
        self::$storageConfig = ConfigLoader::storage();
    }

    /**
     * Creates the set session object
     * 
     * @return \PhpChassis\storage\base\SessionInterface
     * @throws \Exception
     */
    public static function create() : SessionInterface {

        try {
            self::init();
            $sessionTypePrefix = self::$storageConfig->session;
            $sessionTypeClassName = self::SESSION_CLASS_FQN . $sessionTypePrefix . self::SESSION_CLASS_SUFFIX;
            return new $sessionTypeClassName();
        }
        catch(SessionException $e) {
            // TODO Print to log
            throw new \Exception("Session type not found: " . $e->getMessage());
        }
    }
}