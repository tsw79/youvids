<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/1/2019
 * Time: 23:22
 */
namespace phpchassis\cryptography\hash;

use phpchassis\cryptography\hash\base\BaseHash;
use phpchassis\cryptography\hash\base\IHash;

/**
 * Class Md5
 * @package phpchassis-ddd\cryptography\hash
 */
class Md5 extends BaseHash implements IHash {

    public function genSalt() {
        return md5(microtime());
    }
}