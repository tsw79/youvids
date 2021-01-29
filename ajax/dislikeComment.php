<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/30/2019
 * Time: 00:51
 */
$data = (new \youvids\application\controllers\CommentController())->dislike();
echo $data;