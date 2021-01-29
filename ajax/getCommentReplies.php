<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/29/2019
 * Time: 05:45
 */
$data = (new \youvids\application\controllers\CommentController())->replies();
echo $data;