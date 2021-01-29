<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/6/2019
 * Time: 06:34
 */
$data = (new \youvids\application\controllers\VideoController())->trending();

$content = <<<HTML
    <div class="largeVideoGridContainer">
        {$data['grid']}
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");
