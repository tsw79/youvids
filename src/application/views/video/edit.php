<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/9/2019
 * Time: 07:11
 */
$WEB_ROOT = WEB_ROOT;
$PHP_SELF = $_SERVER['PHP_SELF'];

list(
    $player,
    $thumbItemsHtml,
    $video,
    $categoryOptions,
    $flashMessage
) = (new \youvids\application\controllers\VideoController())->edit();

$content = <<<HTML
    <script src="{$WEB_ROOT}/js/videoActions.js"></script>
    <div class="editVideoContainer column">
        <div class="message">{$flashMessage}</div>
    
        <div class="topSection">
            {$player}
            {$thumbItemsHtml}
        </div>
    
        <div class="bottomSection">
            <form method='POST'>
                <div class='form-group'>
                    <input type='text' class='form-control' id='title' name='title' placeholder='Title' value="{$video->title()}" required>
                </div>
                <div class='form-group'>
                    <textarea class='form-control' id='description' name='description' placeholder='Description' rows='3'>{$video->description()}</textarea>
                </div>
                <div class='form-group'>
                    <select class='form-control' id='privacy' name='privacy'>
                        {$video->privacySelectOptions()}
                    </select>
                </div>
                <div class='form-group'>
                    <select class='form-control' id='category' name='category'>
                    {$categoryOptions}
                    </select>
                </div>
                <input type="hidden" name="videoId" value="{$video->id()}">
                <button type='submit' class='btn btn-primary' id='' name='submit'>Save</button>
            </form>
        </div>
    
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");
