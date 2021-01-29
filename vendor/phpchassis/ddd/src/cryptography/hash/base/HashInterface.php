<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/1/2019
 * Time: 23:19
 */
namespace phpchassis\cryptography\hash\base;

/**
 * Interface IHashAlgo
 * @package phpchassis-ddd\cryptography\hash\dto
 */
interface HashInterface {

    /**
     * Hashes a given string
     *
     * @param string $value
     * @return string
     */
    public function hash(string $value)  : string;

    /**
     * Verifies that a given string matches a hash
     *
     * @param string $value     User input
     * @param string $hash      Hash from a storage medium (db, file, etc)
     * @return bool
     */
    public function verify(string $value, string $hash) : bool;
}