<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 01:03
 */
namespace youvids\data;

use youvids\domain\entities\Video;

/**
 * Class VideoPlayer
 * @package youvids\data
 */
class VideoPlayer {

    const AUTOPLAY = true;
    const NO_AUTOPLAY = false;

    /**
     * @var
     */
    private $video;

    /**
     * @var bool
     */
    private $autoPlay;

    /**
     * VideoPlayer constructor.
     * @param Video $video
     * @param bool $autoPlay
     */
    public function __construct(Video $video, $autoPlay = false) {
        $this->video = $video;
        $this->autoPlay = $autoPlay;
    }

    /**
     * Creates the html5 video tags for the player
     * @return string
     */
    public function create(): string {

        $autoPlay = $this->autoPlay ? "autoplay" : "";
        $filePath = $this->video->filePath();

        return "<video class='videoPlayer' controls $autoPlay>
                    <source src='$filePath' type='video/mp4'>
                    Your browser does not support the video tag
                </video>";
    }
}