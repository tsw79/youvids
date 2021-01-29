<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 2/26/2019
 * Time: 19:47
 */
$data = (new \youvids\application\controllers\SiteController())->home();

$content = <<<HTML
    <div class="videoSection">
        {$data['videoGrid']}
    </div>
HTML;

require_once("./src/application/views/layouts/main_layout.inc.php");
?>