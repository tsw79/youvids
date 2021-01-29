<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/11/2019
 * Time: 04:19
 */
$data = (new \youvids\application\controllers\ThumbnailController())->editSelected();
echo json_encode($data);