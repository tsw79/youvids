<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/15/2019
 * Time: 19:13
 */
namespace phpchassis\configs;

use phpchassis\configs\base\ConfigStrategy;

/**
 * Class TextConfig
 */
class TextConfig extends ConfigStrategy {

    public function init() : void { }

    public function load(string $name) : array {
        echo "<br>TextConfig";
    }
}