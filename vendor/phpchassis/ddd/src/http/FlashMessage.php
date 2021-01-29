<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/3/2019
 * Time: 03:13
 */
namespace phpchassis\http;

/**
 * Class Flash
 *  Stores and outputs flash messages to the end-user
 * @package youvids\lib
 */
class FlashMessage {

    /**
     * @var Flash
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $messages = array();

    /**
     * Returns a Singleton
     * @return Flash
     */
    public static function instance() : self {

        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Sets a flash (message)
     * @param string $key
     * @param string $value
     */
    private function set(string $key, string $value): void {
        $this->messages[$key] = $value;

    }

    /**
     * Sets an error flash message
     * @param string $msg
     * @return FlashMessage
     */
    public function error(string $msg): self {
        // TODO Need to override bootstrap css and add an error style. For now, we're using the 'danger' style
        $this->set("danger", $msg);
        return $this;
    }

    /**
     * Sets a success message
     * @param string $msg
     * @return FlashMessage
     */
    public function success(string $msg): self {

        $this->set("success", $msg);
        return $this;
    }

    /**
     * Returns a given flash (message)
     * @param $key
     * @return mixed
     */
    public function get($key) {
        return $this->messages[$key];
    }

    /**
     * Returns a list of flash messages formatted with html
     * @return string
     */
    public function messages(): string {

        $html = '';

        if (!empty($this->messages)) {
            
            foreach ($this->messages as $key => $message) {

                $msgTitle = strtoupper($key);
                $html .= "<div class='alert alert-{$key}'>
                            <strong>{$msgTitle}!</strong> {$message}
                         </div>";
            }
        }
        return $html;
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