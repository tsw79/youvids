<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/11/2019
 * Time: 11:02
 */
namespace phpchassis\http\middleware\file;

use RuntimeException;
use InvalidArgumentException;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class UploadedFile
 * @package phpchassis-ddd\middleware\file
 */
class UploadedFile extends File implements UploadedFileInterface {

    /**
     * Original name of file upload field
     *
     * @var $field
     */
    protected $field;

    /**
     * _FILES[$field]
     *
     * @var array $info
     */
    protected $info;

    /**
     * @var bool $randomize
     */
    protected $randomize;

    /**
     * @var string $movedName
     */
    protected $movedName = '';

    /**
     * @var bool
     */
    protected $moved = false;

    /**
     * UploadedFile constructor.
     *  The constructor allow the definition of the name attribute of the file upload form field, as well as the
     *  corresponding array in $_FILES. We add the last parameter to signal whether or not we want the class to
     *  generate a new random filename once the uploaded file is confirmed.
     *
     * @param $field
     * @param array $info
     * @param bool $randomize
     */
    public function __construct($field, array $info, $randomize = false) {

        $this->field = $field;
        $this->info = $info;
        $this->randomize = $randomize;
    }

    /**
     * Creates a Stream class instance for the temporary or moved file
     *
     * @return Stream
     */
    public function getStream() {

        if (!$this->stream) {

            if ($this->movedName) {
                $this->stream = new Stream($this->movedName);
            }
            else {
                $this->stream = new Stream($this->info['tmp_name']);
            }
        }
        return $this->stream;
    }

    /**
     * This method performs the actual file movement. Note the extensive series of safety checks to help prevent an
     * injection attack. If randomize is not enabled, we use the original user-supplied filename.
     *
     * @param string $targetPath
     * @return bool
     * @throws Exception
     */
    public function moveTo($targetPath) {

        if ($this->moved) {
            throw new Exception(Constants::ERROR_MOVE_DONE);
        }

//        if (!file_exists($targetPath)) {
//            throw new InvalidArgumentException(Constants::ERROR_BAD_DIR);
//        }

        $tempFile = $this->getTmpName() ?? false;

        if (!$tempFile || !file_exists($tempFile)) {
            throw new Exception(Constants::ERROR_BAD_FILE);
        }

        if (!is_uploaded_file($tempFile)) {
            throw new Exception(Constants::ERROR_FILE_NOT);
        }

        if ($this->randomize) {
            // TODO Integrate with system's Cryptography functionality
            $final = bin2hex(random_bytes(8)) . '.txt';
        }
        else {
            $final = $this->info['name'];
        }

        $final = $targetPath . '/' . $final;
        $final = str_replace('//', '/', $final);

        var_dump($tempFile);
        var_dump($final);

        if (!move_uploaded_file($tempFile, $final)) {
            throw new RuntimeException(Constants::ERROR_MOVE_UNABLE);
        }

        $this->movedName = $final;
        $this->moved = true;
        return true;
    }

    /**
     * Returns the moved name
     *
     * @return null|string
     */
    public function getMovedName() {
        return $this->movedName ?? null;
    }

    /**
     * Alias for method getMovedName
     * @return null|string
     */
    public function movedName() {
        return $this->getMovedName();
    }

    /**
     * Returns the size
     * @return mixed|null
     */
    public function getSize() {
        return $this->info['size'] ?? null;
    }

    /**
     * Alias for method getSize
     * @alias
     * @return mixed|null
     */
    public function size() {
        return $this->getSize();
    }

    /**
     * Returns the error code
     * @alias
     * @return int|mixed
     */
    public function getError() {

        if (!$this->moved) {
            return UPLOAD_ERR_OK;
        }
        return $this->info['error'];
    }

    /**
     * Alias for getError method
     * @alias
     * @return int|mixed
     */
    public function error() {
        return $this->getError();
    }

    /**
     * Returns the name of the client's file
     * @return mixed|null
     */
    public function getClientFilename() {
        return $this->info['name'] ?? null;
    }

    /**
     * Alias for getClientFilename method
     * @alias
     * @return mixed|null
     */
    public function clientFilename() {
        return $this->getClientFilename();
    }

    /**
     * Returns the type of the client's file
     * @return mixed|null
     */
    public function getClientMediaType() {
        return $this->info['type'] ?? null;
    }

    /**
     * Alias for getClientMediaType method
     * @alias
     * @return mixed|null
     */
    public function clientMediaType() {
        return $this->getClientMediaType();
    }

    /**
     * Returns true if a valid mime type was found
     *  Note: Alias for getClientMediaType method, but with some added security features.
     */
    public function getClientMimeType() {

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->getTmpName());
        finfo_close($finfo);
        return $mimeType;
    }

    /**
     * @alias
     * @return mixed
     */
    public function clientMimeType() {
        return $this->getClientMimeType();
    }

    /**
     * Returns the tmp_name
     * @return mixed|null
     */
    public function getTmpName() {
        return $this->info['tmp_name'] ?? null;
    }

    /**
     * Alias for getTmpName method
     * @alias
     * @return mixed|null
     */
    public function tmpName() {
        return$this->getTmpName();
    }

    /**
     * Returns the file extension
     * @return string
     */
    public function extension(): string {
        return pathinfo($this->clientFilename(), PATHINFO_EXTENSION);
    }

    /**
     * Returns true if any files were uploaded
     * @return bool
     */
    public static function hasAny(): bool {
        return !empty($_FILES);
    }
}