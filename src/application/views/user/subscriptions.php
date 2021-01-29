<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/12/2019
 * Time: 07:03
 */
$grid = (new \youvids\application\controllers\UserController())->subscriptions();

$content = <<<HTML
    <div class="largeVideoGridContainer">
        {$grid}
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");