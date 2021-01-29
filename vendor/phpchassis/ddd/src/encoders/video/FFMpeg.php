<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/23/2019
 * Time: 00:44
 */
namespace phpchassis\encoders\video;
use phpchassis\exceptions\FileUploadException;

/**
 * Class FFMpeg
 * @package phpchassis\encoders\video
 */
class FFMpeg extends VideoEncoder {

/*
    $encoder = new videoencoder();
    $thumbnail = $encoder->export_thumb("yourfile.mp4");
    $duration = $encoder->get_duration("yourfile.mp4");
    $videofile = $encoder->export_video("yourfile.mp4");
*/

    /**
     * FFMpeg path
     * @var string
     */
    private $ffmpeg;

    /**
     * FFProbe path
     * @var string
     */
    private $ffprobe;

    /**
     * FFMpeg constructor.
     * @param null|string $videopath
     * @param null $ffmpegPath
     * @param $ffprobePath
     */
    public function __construct(string $videopath, string $ffmpegPath, string $ffprobePath) {

        parent::__construct($videopath);
        $this->ffmpeg = $ffmpegPath;
        $this->ffprobe = $ffprobePath;
    }

    /**
     * Calculates and returns the duration of a given video
     * @return int
     */
    public function duration(): int {
        return (int) shell_exec(
            $this->ffprobe . " -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 {$this->video}"
        );

        //$duration = shell_exec(self::FFPROBE_PATH . " -i " . $this->videopath . " 2>&1 | grep \"Duration\" | cut -d ' ' -f 4 | sed s/,//");
        //$duration_array = explode(":", $duration);
        //$secs = $duration_array[2] + 0;
        //$mins = $duration_array[1] + 0;
        //return $secs + ($mins * 60);
    }

    /**
     * Generates thumbnail/s
     * @return bool
     */
    public function thumbnail(string $thumbPath, string $targetPath, string $size, int $numThumbs, int $counter, int $duration): bool {

        /*
         * We're trying to avoid the very start and end parts of the video, which may contain credits, opening titles, etc.
         * ($this->duration() * 0.8) will ignore the first and last couple of seconds of the video
         */
        $interval = ($duration * 0.8) / $numThumbs * $counter;

        /*
         * -i
         * -ss      Where in the video (x-number of seconds) we getting the image from, i.e.
         *              Get the video at the amount of seconds set by $interval
         *
         * -s       Size of the image (thumbnail) to generate
         *
         * vframes  Number of video frames to generate
         */
        $cmd = "{$this->ffmpeg} -i {$targetPath} -ss {$interval} -s {$size} -vframes 1 {$thumbPath} 2>&1";

        /*
         * No need to return, if command fails, continue this process.
         * We could generate a default thumbnail in the case it fails!
         */
        return $this->exec($cmd);
    }

    /**
     * Encodes a video file to the one of the values of the ENCODED_FILE_TYPE` formats
     * @param string $tmpPath
     * @param string $targetPath
     * @return bool
     */
    public function encode(string $targetPath): bool {
        $cmd = $this->ffmpeg . " -i {$this->video} {$targetPath} 2>&1";
        return $this->exec($cmd);
    }



    /**
     * Formats the duration to:
     *      HH:mm:ss or mm:ss (if less than an hour)
     *
     * @param int $duration
     * @return string Formatted time duration
     **/
    public function formatDuration(int $duration = null): string {

        if ($duration == null) {
            $duration = $this->duration();
        }
        // 3600 seconds in an hour
        $hrs = floor($duration / 3600);
        $mins = floor(($duration - ($hrs * 3600)) / 60);
        $secs = floor($duration % 60);

        // If video file's duration is less than an hour, don't display anything for hour
        $hrs = ($hrs < 1) ? "" : $hrs . ":";
        // If video file's duration is less than 10 minutes, prefix the minutes with a zero
        $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
        // If video file's duration is less than 10 seconds, prefix the seconds with a zero
        $secs = ($secs < 10) ? "0" . $secs : $secs;
        // Set the formatted duration
        $duration = $hrs . $mins . $secs;

        return $duration;
    }
}