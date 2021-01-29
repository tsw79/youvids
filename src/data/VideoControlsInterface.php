<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 07:19
 */
namespace youvids\data;

/**
 * Interface VideoControlsInterface
 * @package youvids\data
 */
interface VideoControlsInterface {

    /**
     * Returns the Video's Controls
     * @return array
     */
    public function create()/*: array*/;
}