<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/15/2019
 * Time: 19:12
 */
namespace phpchassis\configs;

use phpchassis\configs\base\ConfigStrategy;

/**
 * Class PhpConfig (Based on the Strategy pattern)
 */
class PhpConfig extends ConfigStrategy {

    public function init() {
        return;
    }

    public function load(string $name) : array {

        $configFile = self::CONFIGS_DIR . ucfirst($name) . self::CONFIGS_EXT;
        $configSettings[$name] = require_once($configFile);
        return $configSettings;
    }
}