<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/23/2019
 * Time: 06:06
 */
return [
  /*
    * Video encoder config settings
    */
  "video" =>  [
    /*
     * FFMpeg encoder
     */
    "ffmpeg"  => [
      /*
       * Class to use
       */
      "class"   => "",
      /*
       * FFMpeg encoder's path
       */
      "encoder" => ROOT_DIR  ."/vendor/ffmpeg/bin/ffmpeg",
      /*
       * FFProbe's path
       */
      "probe"   => ROOT_DIR . "/vendor/ffmpeg/bin/ffprobe"
    ],
  ],
  /*
   * Audio encoder config settings
   */
  "audio" =>  [

  ]
];