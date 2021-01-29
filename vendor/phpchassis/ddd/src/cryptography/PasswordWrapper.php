<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/3/2019
 * Time: 00:08
 */
namespace phpchassis\cryptography;

use phpchassis\cryptography\ {PasswordFactory, Security};

/**
 * Class PasswordService is a wrapper for the built-in PHP password functionality
 *
 * @package phpchassis-ddd\cryptography
 */
class PasswordWrapper extends Security {

    /**
     * @var PasswordWrapper $instance
     */
    private static $instance;

    /**
     * @var IHash $algo
     */
    private static $algo;

    // Ref:  https://www.php.net/manual/en/ref.password.php
    // -----------------------------------------------------
    // 1. password_hash() – hashes the password.
    // 2. password_verify() – verifies a password against its hash.
    // 3. password_needs_rehash() – used for password rehashing
    // 4. password_get_info() – returns the name of the hashing algorithm and various options used while hashing.

   public function __construct() {
      //  $this->algo = PasswordFactory::create();
   }

    public static function instance(): PasswordWrapper {

        if (self::$instance == null) {

            self::$instance = new self();
            self::$algo = PasswordFactory::create();
        }
        return self::$instance;
    }

    /**
     * Generates a (hashed) password from a given value
     *
     * @param string $input
     * @return string
     */
    public function generate(string $input) : string {
        return self::$algo->hash($input);
    }

    /**
     * Checks for a match between two passwords. True if match is found. False otherwise.
     *
     * @param string $input     User input
     * @param string $hash      Saved hash (from db, file, etc)
     * @return bool
     */
    public function match(string $input, string $hash) : bool {
        return self::$algo->verify($input, $hash);
    }

    // Returns if the password meets the strength requirements
    public function check() {

    }

    // Generates a random password with a specific strength
    public function random() {

    }
}