<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/9/2019
 * Time: 05:30
 */
$data = (new \youvids\application\controllers\VideoController())->search();

$content = <<<HTML
    <div class="largeVideoGridContainer">
        {$data['grid']}
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");
