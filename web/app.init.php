<?php
/**
 * Common functions (application-wide)
 *
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/6/2019
 * Time: 17:59
 */
defined('ENV') or define('ENV', 'dev');
define("DS", DIRECTORY_SEPARATOR);

/*
 * N1:  Need to go two directories up to get Root directory - this one gives me the includes dir
 *      PHP < 5.3       :   $upOne = realpath(dirname(__FILE__) . '/..');
 *      PHP 5.3 to 5.6  :   $upOne = realpath(__DIR__ . '/..');
 *      PHP >= 7.0      :   $upOne = dirname(__DIR__, 1);
 */
define("ROOT_URL", $_SERVER["HTTP_HOST"]);
define("ROOT_DIR", realpath(dirname(__FILE__, 2))); // N1
define("WEB_ROOT", "/web");
define("RUNTIME_DIR", ROOT_DIR . "/runtime");

//define("BASE_PATH", __DIR__ . DS . "..");
//define("APP_ROOT", "/dev.youvids");
//define("VENDOR_ROOT", "/dev.youvids/vendor");
//define("PHPCHASSIS_DIR", ROOT_DIR . "/vendor/phpchassis-ddd");

require(ROOT_DIR . "/vendor/autoload.php");

// Set up the error and exception handler
\phpchassis\error\Handler::setup(RUNTIME_DIR . '/logs/', 'error.log');