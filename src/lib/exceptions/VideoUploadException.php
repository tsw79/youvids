<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/1/2019
 * Time: 22:17
 */
namespace youvids\lib\exceptions;

/**
 * Class VideoUploadException
 * @package Youvids\exceptions
 */
class VideoUploadException extends \Exception {

//    private $fileUploadErrMessage;
//
//    public function __construct($errCode) {
//
//        $this->fileUploadErrorMessage($errCode);
//        $message = $this->fileUploadErrMessage->errMessage();
//        parent::__construct($message, $errCode);
//
//    }
//
//    public function fileUploadErrorMessage($errCode = null) {
//
//        // Getter
//        if($errCode === null) {
//            return $this->fileUploadErrorMessage;
//        }
//        // Setter
//        else {
//            $this->fileUploadErrMessage = new FileUploadErrMessage($errCode);
//        }
//    }
}