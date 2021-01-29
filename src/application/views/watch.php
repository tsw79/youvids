 <?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/7/2019
 * Time: 08:59
 */
 $WEB_ROOT = WEB_ROOT;

list(
    $player,
    $video,
    $uploadedBy,
    $primaryControls,
    $secondaryControls,
    $commentSection,
    $grid
) = (new \youvids\application\controllers\VideoPlayerController())->watch();

$content = <<<HTML
    <script src="{$WEB_ROOT}/js/playerActions.js"></script>
    <script src="{$WEB_ROOT}/js/commentActions.js"></script>
    <div class="watchLeftColumn">
        {$player}
        <div class='videoInfo'>
            <h1>{$video->title()}</h1>
            <div class='bottomSection'>
                <span class='viewCount'>{$video->views()} views</span>
                <div class='controls'>
                    {$primaryControls['likeButton']}
                    {$primaryControls['dislikeButton']}
                </div>
            </div>
        </div>
                
        <div class='secondaryInfo'>
            <div class='topRow'>
                {$secondaryControls['profileButton']}
                <div class='uploadInfo'>
                        <span class='owner'>
                        <a href='profile.php?username={$uploadedBy->username()}'>
                            {$uploadedBy->username()}
                        </a>
                    </span>
                    <span class='date'>Published on {$video->displayUploaded()}</span>
                </div>
                {$secondaryControls['actionButton']}
            </div>
            <div class='descriptionContainer'>
                {$video->description()}
            </div>
        </div>
        
        <div class='commentSection'>
            {$commentSection}
        </div>
                
    </div>
    <div class="suggestions">
        {$grid}
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");
