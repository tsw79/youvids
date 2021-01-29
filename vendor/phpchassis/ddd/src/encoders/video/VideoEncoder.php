<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/23/2019
 * Time: 00:49
 */
namespace phpchassis\encoders\video;

use phpchassis\encoders\ {BaseEncoder, EncoderInterface};
use phpchassis\exceptions\FileUploadException;

/**
 * Class VideoEncoder
 * @package phpchassis\encoders\video
 */
abstract class VideoEncoder extends BaseEncoder implements EncoderInterface {

    /**
     * @var string Video path
     */
    protected $video;

    /**
     * VideoEncoder constructor.
     * @param string|null $videoPath
     */
    public function __construct(string $videoPath = null) {
        $this->video = $videoPath;
    }

    /**
     * Executes an external program
     * @param null $cmd
     * @return bool
     */
    public function exec($cmd = null): bool {

        if($cmd === null) {
            echo "No command passed.";
            return false;
        }

        $report = array();
        exec($cmd, $report, $returnCode);

        if ($returnCode != 0) {

            // Command failed
            foreach ($report as $r) {
                echo $r . "<br />";
            }
            return false;
        }
        return true;
    }
}