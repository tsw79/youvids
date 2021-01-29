<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/20/2019
 * Time: 16:58
 */
namespace phpchassis\logger;

use Psr\Log\LoggerInterface;

/**
 * Class FileLog
 * @package phpchassis-ddd\logger
 */
class FileLog extends BaseLog implements LoggerInterface {

    /**
     * @var $fileHandle
     */
    private $fileHandle;

    /**
     * @var array $logConfig
     */
    protected $logConfig;

    /**
     * FileLog constructor.
     * @param array $logConfig
     */
    public function __construct(object $logConfig) {    var_dump($logConfig);

        $this->logConfig = $logConfig;
        // Gets the file handle
        $this->fileHandle = fopen($this->logConfig->filename, 'a');
    }

    /**
     * Logs with an arbitrary level.
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array()) {

        $now = date("d-M-Y, h:i:s a");
        fwrite($this->fileHandle, "{$now} {$level} {$message}" . PHP_EOL);
        echo 'Successfully logged to text file';
    }

    /**
     * Closes the file before destroying the object
     */
    public function __destruct() {
        fclose($this->fileHandle);
    }
}