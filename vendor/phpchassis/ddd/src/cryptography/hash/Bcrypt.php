<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/1/2019
 * Time: 23:19
 */
namespace phpchassis\cryptography\hash;

use phpchassis\cryptography\hash\base\BaseHash;
use phpchassis\cryptography\hash\base\HashInterface;

/**
 * Class Bcrypt
 * @package phpchassis-ddd\cryptography\hash
 */
class Bcrypt extends BaseHash implements HashInterface {

    /**
     * PASSWORD_BCRYPT uses the algorithm CRYPT_BLOWFISH to create the hash. This will produce a standard crypt()
     * compatible hash using the “$2y$” identifier. The result will always be a 60 character string or on FALSE on failure.
     *      Ref:  https://www.php.net/manual/en/function.password-hash.php
     *
     * @var int $algo     Name of algorithm
     */
    protected $algo = PASSWORD_BCRYPT;

    /**
     * Hashes a given string
     *
     * @param string $value
     * @return string
     */
    public function hash(string $value) : string {

        $hashed = password_hash($value, $this->algo);
        return $hashed;
    }

    /**
     * Verifies that a given string matches a hash
     *
     * @param string $value     User input
     * @param string $hash      Hash from a storage medium (db, file, etc)
     * @return bool
     */
    public function verify(string $value, string $hash) : bool {

        // If the password input matched the hashed password...
        if(password_verify($value, $hash)) {
            return true;
        }
        return false;
    }
}