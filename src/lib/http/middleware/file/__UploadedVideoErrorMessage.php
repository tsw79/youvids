<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/1/2019
 * Time: 22:31
 */
namespace youvids\lib\http\middleware\file;

/**
 * Class UploadedVideoErrorMessage
 * @package YouVids\file
 */
class UploadedVideoErrorMessage {

  private $errCode;

  public function __construct($errCode = null) {
      $this->errorCode($errCode);
  }

  public function errorCode($errCode = null) {
    // Getter
    if($errCode === null) {
      return $this->errCode;
    }
    // Setter
    else {
      $this->errCode = $errCode;
    }
  }

  /**
   * Error messages
   */
  public function errMessage() {

    switch ($this->errCode) {
      case UPLOAD_ERR_INI_SIZE:
        $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
        break;
      case UPLOAD_ERR_FORM_SIZE:
        $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
        break;
      case UPLOAD_ERR_PARTIAL:
        $message = "The uploaded file was only partially uploaded";
        break;
      case UPLOAD_ERR_NO_FILE:
        $message = "No file was uploaded";
        break;
      case UPLOAD_ERR_NO_TMP_DIR:
        $message = "Missing a temporary folder";
        break;
      case UPLOAD_ERR_CANT_WRITE:
        $message = "Failed to write file to disk";
        break;
      case UPLOAD_ERR_EXTENSION:
        $message = "File upload stopped by extension";
        break;
      default:
        $message = "Unknown upload error";
        break;
    }
    return $message;
  }
}