<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/1/2019
 * Time: 04:12
 */
namespace youvids\application\services;

use youvids\lib\http\middleware\file\UploadedVideo;
use phpchassis\data\repository\RepositoryInterface;
use phpchassis\encoders\video\FFMpeg;
use phpchassis\data\dto\RequestDataInterface;
use phpchassis\data\service\ {ApplicationService, ApplicationServiceInterface};
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class UploadVideoService
 * @package youvids\domain\services
 */
class UploadVideoService extends ApplicationService implements ApplicationServiceInterface {

    /**
     * @var string
     */
    private $encoderPath;

    /**
     * @var string
     */
    private $probePath;

    /**
     * @var EncoderInterface
     */
    private $videoEncoder;

    /**
     * UploadVideoService constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository) {
        parent::__construct($repository);
        $this->init();
    }

    /**
     * Initializes and sets up the class and its attributes
     */
    private function init() {

        // @TODO We need to get these settings from the Config Loader
        $this->encoderPath = ROOT_DIR . "/vendor/ffmpeg/bin/ffmpeg";
        $this->probePath = ROOT_DIR . "/vendor/ffmpeg/bin/ffprobe";
    }

    /**
     * Executes the Upload Video Service
     *
     *      Steps:
     *      ------
     *      1.)  Create file upload data
     *      2.)  Upload and process video data
     *      3.)  Check if upload was successful
     *
     * @param RequestDataInterface $requestData
     * @return bool
     */
    public function execute(RequestDataInterface $requestData) {

        $uploadedVideo = $requestData->uploadedVideo;                           //var_dump($uploadedVideo->targetFilePath());exit;

        if (!$uploadedVideo instanceof UploadedFileInterface) {
            throw new \TypeError("UploadedVideo instance expected.");
        }

        if ($uploadedVideo->isValid()) {

            if(!$uploadedVideo->moveToTmpPath()) {
                echo "Error: Failed to move file to tmp location.\n";
                return false;
            }

            // @TODO Hard-coded for now! Need to load the EncoderInterface class dynamically by using the settings in the configuration file.
            $this->videoEncoder = new FFMpeg(
                $uploadedVideo->tmpPath(),
                $this->encoderPath,
                $this->probePath
            );

            $success = $this->videoEncoder->encode($uploadedVideo->targetFilePath());

            if (!$success) {
                echo "Upload failed due to conversion error.\n";
                return false;
            }

            $duration = $this->videoEncoder->duration();

            // Insert video details into the Video table
            $videoId = $this->repository->addOne(
                $requestData->title,
                $requestData->description,
                $requestData->privacy,
                $uploadedVideo->targetFilePath(),
                $requestData->category,
                $this->videoEncoder->formatDuration($duration)
            );

            if (!$videoId) {
                echo "Insert failed";
                return false;
            }

            if (!$this->genThumbnails($uploadedVideo, $videoId, $duration)) {
                echo "Upload failed:  Could not generate thumbnails\n";
                return false;
            }

            $uploadedVideo->deleteTmpVideo();
            return true;
        }

        return false;
    }

    /**
     * Generates the thumbnails and inserts data into DB
     * @param UploadedVideo $uploadedVideo
     * @param int $videoId
     * @param int $duration
     * @return bool
     */
    private function genThumbnails(UploadedVideo $uploadedVideo, int $videoId, int $duration) {

        $numThumbs = UploadedVideo::NUM_THUMBNAILS;

        // Generate the thumbnails and insert each one into the DB
        for($i = 1; $i <= $numThumbs; $i++) {

            $thumbName = $uploadedVideo->uniqueName() . "." . UploadedVideo::THUMBNAIL_EXT;
            $thumbPath = UploadedVideo::THUMBNAIL_DIR . "{$videoId}-{$thumbName}";

            $success = $this->videoEncoder->thumbnail(
                $thumbPath,
                $uploadedVideo->targetFilePath(),
                UploadedVideo::THUMBNAIL_SIZE,
                $numThumbs,
                $i,
                $duration
            );

            if (!$success) {
                return false;
            }

            // Insert into the Thumbnails table
            $success = $this->params->thumbnailRepo->addOne(
                $videoId,
                $thumbPath,
                $i == 1 ? 1 : 0     // Select only the first thumbnail
            );

            if (!$success) {
                echo "Error inserting thumbnail.\n";
                return false;
            }
        }
        return true;
    }
}