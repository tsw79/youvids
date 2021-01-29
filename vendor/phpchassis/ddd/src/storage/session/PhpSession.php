<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/4/2019
 * Time: 18:23
 */
namespace phpchassis\storage\session;

use phpchassis\exceptions\SessionException;
use phpchassis\storage\session\Session;
use phpchassis\storage\base\SessionInterface;


/**
 * Class PhpSession
 * @package phpchassis-ddd\storage\session
 */
class PhpSession extends Session implements SessionInterface {

    // TODO (1) Need to add absolute path (2) Need to move to the config file
    const FILEPATH = "/tmp/php_session.txt";

    /**
     * Starts or opens a new session
     *  To help protect a session is to use session_regenerate_id(), which invalidates the existing
     *  PHP session identifier and generates a new one. Thus, if an attacker were to obtain the session identifier
     *  through illegal means, the window of time in which any given session identifier is valid is kept to a minimum.
     */
    public function open() {

        if(!$this->isActive()) {
            session_start();
            //session_regenerate_id();
        }
    }

    /**
     * Close session
     */
    public function close() {
        return $this->destroy();
    }

    /**
     * Read a session data from file
     * @param string $name
     * @return mixed
     */
    public function read() {

        try {
            $this->open();
            $fileHandle = fopen(self::FILEPATH, 'r');
            $sessionData = fread($fileHandle, 4096);
            fclose($fileHandle);
            return session_decode($sessionData);
        }
        catch (SessionException $e) {
            echo "Error reading session from file: " . $e->getMessage();
        }
    }

    // TODO INCUBATION
    public function timeout() {

        /**  Ref: https://www.sitepoint.com/php-sessions/  **/

        // set time-out period (in seconds)
        $inactive = 600;

        // check to see if $_SESSION["timeout"] is set
        if (isset($_SESSION["timeout"])) {
            // calculate the session's "time to live"
            $sessionTTL = time() - $_SESSION["timeout"];
            if ($sessionTTL > $inactive) {
                session_destroy();
                header("Location: /logout.php");
            }
        }
        $_SESSION["timeout"] = time();
    }

    /**
     * Saves a sessions state to file
     * @param string $name
     * @param $value
     */
    public function write() {

        try {
            $this->open();
            $sessionData = session_encode();            // Returns an encoded string containing the session data.
            $fileHandle = fopen(self::FILEPATH, "w+");  // Open the file
            fwrite($fileHandle, $sessionData);          // Write the session data to file
            fclose($fileHandle);
        }
        catch(SessionException $e) {
            echo "Error writing session to file: " . $e->getMessage();
        }
    }

    /**
     * Destroy an active session
     */
    public function destroy() {

        if($this->isActive()) {
            return session_destroy();
        }
        return false;
    }

    /**
     * Removes a value from the session
     */
    public function remove($name) {
        unset($_SESSION[$name]);
    }

    /**
     * Returns the current session status
     *
     *      session_status() values:
     *      ------------------------
     *      0 = PHP_SESSION_DISABLED
     *      1 = PHP_SESSION_NONE
     *      2 = PHP_SESSION_ACTIVE
     *
     * @return bool
     */
    public function isActive() : bool {
        return (session_status() === PHP_SESSION_DISABLED || session_status() === PHP_SESSION_NONE) ? false : true;
    }

    /**
     * Checks if key exists in session
     */
    public function has($name) : bool {
        return ($this->array_key_isset($name, $_SESSION) && !is_null($_SESSION[$name]));
    }

    /**
     * Retrieves a value from the session
     */
    public function get(string $name) {
        return $this->has($name) ? $_SESSION[$name] : false;
    }

    /**
     * Sets a value in the session
     */
    public function set(string $name, $value) {
        //$this->open();
        $_SESSION[$name] = $value;
    }
}