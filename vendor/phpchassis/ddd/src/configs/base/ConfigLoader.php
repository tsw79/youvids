<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/15/2019
 * Time: 19:18
 */
namespace phpchassis\configs\base;

use phpchassis\configs\base\ConfigContext;

/**
 * Class ConfigLoader (Based on the Strategy pattern)
 */
class ConfigLoader {

    /**
     * @TODO This value neeeds to come from a global setting, like: ['config-type' => 'php' ]
     *
     * @var string
     */
    private $configType = 'php';

    /**
     * Suffix for all config concrete classes
     */
    const CONFIG_FILE_SUFFIX = "Config";

    /**
     * Need to specify the fully qualified name as we're instantiating the object by using a string
     *  Ref:  https://www.php.net/manual/en/language.namespaces.rules.php
     */
    const CONFIG_FULLY_QUALIFIED_NAME = "\\phpchassis\\configs\\";

    /**
     * Holds a single instance of ConfigLoader
     * @var ConfigLoader $instance
     */
    private static $instance = null;

    /**
     * Holds a db config
     *
     * @var object $dbConfig
     */
    private static $dbConfig = null;

    /**
     * Holds a app config
     *
     * @var object $appConfig
     */
    private static $appConfig = null;

    /**
     * Holds a params config
     *
     * @var object $paramsConfig
     */
    private static $paramsConfig = null;

    /**
     * Handle to the ConfigContext class
     *
     * @var ConfigContext
     */
    private $context;

    /**
     * Configurations
     *
     * @var array $config
     */
    private $configs = array();

    /**
     * Returns a Singleton instance of this class
     * @return ConfigLoader|static
     */
    public static function instance() : self {

        if (null === self::$instance) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function loadConfig(string $name) {

        $this->configs = array_merge(
            $this->configs,
            $this->context->confStrategy()->load($name)
        );
    }

    // @TODO
    // Perhaps I should load the configuration into the $appConfig var at init() stage, then every other
    // config gets their specific config from the $appConfig var???

    private function init() {

        $object = $this->initStrategy();

        $this->context = new ConfigContext();
        $this->context->configInterface($object);
    }

    private function initStrategy() : ConfigStrategy {

        $strategyStr = self::CONFIG_FULLY_QUALIFIED_NAME . ucfirst($this->configType) . self::CONFIG_FILE_SUFFIX;
        return new $strategyStr();
    }

    /**
     * Gets the app config
     */
    public static function app() : array {

        if(is_null(self::$appConfig)) {

            $self = self::instance();
            $self->loadConfig("app");
            self::$appConfig = $self->configs()["app"];
        }
        return self::$appConfig;
    }

    /**
     * Gets the database config
     */
    public static function db() {

        if(is_null(self::$dbConfig)) {

            $appConf = self::app();
            self::$dbConfig = (object) $appConf["db"][ENV];
        }
        return self::$dbConfig;
    }

    /**
     * Gets the params config
     */
    public static function params() {

        if(is_null(self::$paramsConfig)) {

            $appConf = self::app();
            self::$paramsConfig = (object) $appConf["params"];
        }
        return self::$paramsConfig;
    }

    /**
     * Gets the security config
     */
    public static function security() {

        $appConf = self::app();
        return (object) $appConf["security"];
    }

    /**
     * Gets the log config
     */
    public static function log() {

        $appConf = self::app();
        return (object) $appConf["log"];
    }

    /**
     * Gets the cache config
     */
    public static function cache() {

        $appConf = self::app();
        return (object) $appConf["cache"];
    }

    /**
     * Gets the security config
     */
    public static function storage() {

        $appConf = self::app();
        return (object) $appConf["storage"];
    }

    /**
     * Gets the search results config for a given (search) type
     * @param string|null $type
     * @return mixed
     */
    public static function searchResults(string $type = null) {

        $appConf = self::app();
        $searchResultsConf = $appConf["search.results"];
        return (null !== $type) ? $searchResultsConf[$type] : $searchResultsConf;
    }

    public function configs($configs = null) {

        // Getter
        if($configs === null) {
            return $this->configs;
        }
        // Setter
        else {
            $this->configs = $configs;
        }
    }

    /**
     * DatabaseConnection constructor.
     *  Prevent creating multiple instances due to "private" constructor
     */
    private function __construct() {}

    /**
     * Prevent the instance from being cloned
     */
    private function __clone() {}

    /**
     * Prevent from being unserialized
     */
    private function __wakeup () {}
}