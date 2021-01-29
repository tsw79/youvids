<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/10/2019
 * Time: 02:09
 */
$data = (new \youvids\application\controllers\VideoController())->liked();

$content = <<<HTML
    <div class="largeVideoGridContainer">
        {$data['grid']}
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");



