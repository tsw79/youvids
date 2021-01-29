<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/1/2019
 * Time: 17:49
 */
namespace youvids\lib\http\middleware\file;

use phpchassis\http\FormRequest;
use phpchassis\http\middleware\file\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class VideoFile
 * @package Youvids\file
 */
class UploadedVideo extends UploadedFile {

  /*
    *  @TODO Move these settings to the config file
    *  @TODO    SITE_ROOT needs to be made accessible in the configs file
    */

  /**
   * Use this to replace any spaces in a file's name
   */
  const SEPERATOR = '_';

  /**
   * @var string UPLOAD DIR
   */
  const UPLOAD_DIR = ROOT_DIR . "/web/uploads/";

  /**
   * @var string TARGET_DIR
   */
  const UPLOAD_VIDEOS_DIR = self::UPLOAD_DIR . "videos/";

  /**
   * @var string UPLOAD TMP DIR
   */
  const TMP_DIR = self::UPLOAD_DIR . "tmp/";

  /**
   * @var string TARGET_DIR
   */
  const THUMBNAIL_DIR = self::UPLOAD_VIDEOS_DIR . "thumbnails/";

  /**
   * File type to convert uploaded videos to
   */
  const ENCODED_FILE_TYPE = "mp4";

  /**
   * @var string TARGET_DIR
   */
  const THUMBNAIL_EXT = "jpg";

  /**
   * @var string THUMBNAIL_SIZE
   */
  const THUMBNAIL_SIZE = "210x118";

  /**
   * Number of thumbnails to generate
   * @var string NUM_THUMBNAILS
   */
  const NUM_THUMBNAILS = 3;

  /**
   * File size limit for videos
   */
  const SIZE_LIMIT = 32000000;   // Set to 32mb according to php.ini

  /**
   * List of supported file types
   */
  const SUPPORTED_FILE_TYPES = [
    "video/mp4",
    "video/flv",
    "video/webm",
    "video/mkv",
    "video/vob",
    "video/ogv",
    "video/ogg",
    "video/avi",
    "video/mov",
    "video/mpeg",
    "video/mpg",
    /*
      * Windows Media Video types
      */
    "video/wmv",
    "video/x-ms-asf"
  ];

  /**
   * Duration of the video
   * @var string $duration
   */
//    private $duration;

  /**
   * Path to tmp file
   * @var string
   */
  private $tmpPath = null;

  /**
   * @var string
   */
  private $targetPath = null;

  /**
   * Returns a list of Uploaded Videos
   * @param $request
   * @return array
   */
  public static function createByRequest(FormRequest $request): array {
    $uploadedVideos = array();
    foreach ($request->getUploadedFileInfo() as $field => $value) {
      $uploadedVideos[$field] = new UploadedVideo($field, $value);
    }
    return $uploadedVideos;
  }

  /**
   * Returns the tmp file path
   * @return string
   */
  public function tmpPath(): string {
    if ($this->tmpPath == null) {
      $this->tmpPath = self::TMP_DIR . $this->uniqueName(false);
    }
    return $this->tmpPath;
  }

  /**
   * Returns the target file path
   * @return string
   */
  public function targetFilePath(): string {
    if ($this->targetPath == null) {
      $this->targetPath = self::UPLOAD_VIDEOS_DIR . $this->uniqueName() . '.' . self::ENCODED_FILE_TYPE;
    }
    return $this->targetPath;
  }

  /**
   * Move the uploaded video to a tmp path
   * @return bool
   * @throws \phpchassis\middleware\Exception
   */
  public function moveToTmpPath() {

    // @TODO Need to make this work with PSR-7 middleware's UploadedFile moveTo() method
    //return $this->moveTo($this->tmpPath());
    //return $this->moveTo($this->targetFilePath());

    var_dump($this->tmpName());
    var_dump($this->tmpPath());
    return move_uploaded_file($this->tmpName(), $this->tmpPath());
  }

  /**
   * Checks the validity of this video
   * @return bool
   */
  public function isValid(): bool {

    //$videoType = $video->extension();

    if (!$this->isSizeValid()) {
      echo "File too large. Can't be more than " . self::SIZE_LIMIT . "bytes";
      return false;
    }
    elseif (!$this->isTypeSupported()) {
      //throw new FileUploadException("'{$encodedFileType}' file types are not supported.");
      echo "Invalid file type.";
      return false;
    }
    elseif ($this->hasError()) {
      echo "Uploaded video has an error.";
      return false;
    }
    return true;
  }

  /**
   * Returns true if size is valid
   * @return bool
   */
  private function isSizeValid(): bool {
    return $this->size() <= self::SIZE_LIMIT;
  }

  /**
   * Returns true if file type is supported
   * @param string $type
   * @return bool
   */
  private function isTypeSupported($type = null): bool {
    if ($type == null) {
      $type = $this->clientMimeType();
    }
    return in_array($type, self::SUPPORTED_FILE_TYPES);
  }

  /**
   * Returns true if a video file was successfully deleted from the file system
   * @return bool
   */
  public function deleteTmpVideo(): bool {
    if(!unlink($this->tmpPath())) {
      echo "Error: Could not delete file: {$this->tmpPath()}\n";
      return false;
    }
    return true;
  }

  /**
   * Returns a unique name
   * @param bool $idAsName
   * @return string
   */
  public function uniqueName($idAsName = true): string {
    return $idAsName
      ? uniqid()
      : uniqid() . self::SEPERATOR . str_replace(" ", self::SEPERATOR, self::SEPERATOR . $this->clientFilename());
  }

  /**
   * Returns true if the uploaded video has an error
   * @return bool
   */
  public function hasError(): bool {
    return $this->error();
  }

  /**
   * This method takes $uploadedFileInfo and creates UploadedFile objects as uploaded files are supposed to be
   * represented as independent UploadedFile objects.
   *
   * @return mixed
   */
  public function getUploadedVideos() {

    if (!$this->uploadedFileObjs) {
      foreach ($this->getUploadedFileInfo() as $field => $value) {
        $this->uploadedFileObjs[$field] = new UploadedVideo($field, $value);
      }
    }
    return $this->uploadedFileObjs;
  }

  /**
   * Alias for method getUploadedVideos
   * @alias
   * @return mixed
   */
  public function uploadedVideos(): array {
    return $this->getUploadedVideos();
  }

  /**
   * Alias for method getUploadedVideos
   * @alias
   * @return mixed
   */
  public function videos(): array {
    return $this->getUploadedVideos();
  }

  /**
   * Returns a single instance of UploadedFile
   * @return UploadedFileInterface
   */
  public function getUploadedVideo(): UploadedFileInterface {

    if (!$this->uploadedFileObj) {
        $fileInfo = $this->getUploadedFileInfo();
        $field = key($fileInfo);
        $this->uploadedFileObj = new UploadedVideo($field, $fileInfo[$field]);
    }
    return $this->uploadedFileObj;
  }

  /**
   * Alias for method getUploadedVideo
   * @alias
   * @return UploadedFileInterface
   */
  public function uploadedVideo(): UploadedFileInterface {
    return $this->getUploadedVideo();
  }

  /**
   * Alias for method getUploadedVideo
   * @alias
   * @return UploadedFileInterface
   */
  public function video(): UploadedFileInterface {
    return $this->getUploadedVideo();
  }
}