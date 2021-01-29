<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/22/2019
 * Time: 04:22
 */
namespace youvids\application\dto;

use phpchassis\data\dto\ {RequestData, RequestDataInterface};

/**
 * Class UploadVideoRequest
 * @package youvids\data\dto
 */
class UploadVideoRequest extends RequestData implements RequestDataInterface {

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $privacy;

    /**
     * @var int
     */
    public $category;

    /**
     * @var
     */
    public $uploadedVideo;

    /**
     * UploadVideoRequest constructor.
     * @param $title
     * @param $description
     * @param $privacy
     * @param $category
     * @param $videoFile
     */
    public function __construct($title, $description, $privacy, $category, $uploadedVideo) {

        $this->title = $title;
        $this->description = $description;
        $this->privacy = $privacy;
        $this->category = $category;
        $this->uploadedVideo = $uploadedVideo;
    }

    /**
     * Returns the request as an array
     * @return array
     */
    public function toArray(): array {

        return [
            "title"         => $this->title,
            "description"   => $this->description,
            "privacy"       => $this->privacy,
            "category"      => $this->category,
            "uploadedVideo" => $this->uploadedVideo
        ];
    }
}