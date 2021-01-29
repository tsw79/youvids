<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/5/2019
 * Time: 05:04
 */

// @TODO Need to send a token for CSRF

$data = (new \youvids\application\controllers\VideoController())->like();
echo json_encode($data);