<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/15/2019
 * Time: 19:09
 */
namespace phpchassis\configs\base;

/**
 * Interface ConfigStrategy (Based on the Strategy pattern)
 */
abstract class ConfigStrategy {

    const CONFIGS_EXT = ".cfg.php";
    const CONFIGS_DIR = ROOT_DIR . "/config/";

    abstract public function init();
    abstract public function load(string $name) : array;
}