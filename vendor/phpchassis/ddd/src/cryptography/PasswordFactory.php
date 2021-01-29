<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/3/2019
 * Time: 23:11
 */
namespace phpchassis\cryptography;

use phpchassis\configs\base\ConfigLoader;
use phpchassis\exceptions\PasswordException;

/**
 * Class PasswordFactory
 * @package phpchassis\cryptography
 */
class PasswordFactory {

    /**
     * PASSWD_ALGO_FQN = Password Algorithm Fully Qualified Name
     * Need to specify the fully qualified name as we're instantiating the object by using a string
     *  Ref:  https://www.php.net/manual/en/language.namespaces.rules.php
     */
    const PASSWD_ALGO_FQN = "\\phpchassis\\cryptography\\hash\\";

    /**
     * Database configuration settings
     *
     * @var object $dbConfig
     */
    private static $securityConfig;

    private static function init() {
        self::$securityConfig = ConfigLoader::security();
    }

    //public static function create(): IHash {
    public static function create() {

        try {
            self::init();
            $hashAlgo = self::$securityConfig->password;
            $hashAlgoClassName = self::PASSWD_ALGO_FQN . $hashAlgo;
            return new $hashAlgoClassName();
        }
        catch(PasswordException $e) {

            // @TODO Print to log
            throw new \Exception("Password hash not found: " . $e->getMessage());
        }
    }
}