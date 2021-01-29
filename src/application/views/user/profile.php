<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/2/2019
 * Time: 04:23
 */
$data = (new \youvids\application\controllers\UserController())->profile();
$photoContainer = (object) $data["photoContainer"];
$header = (object) $data["header"];
$content = (object) $data["content"];
$about = (object) $data["content"]["about"];

$webroot = WEB_ROOT;

$content = <<<HTML
    <div class='profileContainer'>

        <div class='coverPhotoContainer'>
            <img src='{$photoContainer->src}' class='coverPhoto'>
            <span class='channelName'>{$photoContainer->channel}</span>
        </div>
        
        <div class='profileHeader'>
            <div class='userInfoContainer'>
                <img class='profileImage' src='{$header->src}'>
                <div class='userInfo'>
                    <span class='title'>{$header->fullName}</span>
                    <span class='subscriberCount'>{$header->count} subscribers</span>
                </div>
            </div>

            <div class='buttonContainer'>
                <div class='buttonItem'>    
                    {$header->button}
                </div>
            </div>
        </div>
        
        <ul class='nav nav-tabs' role='tablist'>
            <li class='nav-item'>
            <a class='nav-link active' id='videos-tab' data-toggle='tab' 
                href='#videos' role='tab' aria-controls='videos' aria-selected='true'>VIDEOS</a>
            </li>
            <li class='nav-item'>
            <a class='nav-link' id='about-tab' data-toggle='tab' href='#about' role='tab' 
                aria-controls='about' aria-selected='false'>ABOUT</a>
            </li>
        </ul>
        
        <div class='tab-content channelContent'>
            <div class='tab-pane fade show active' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
                {$content->videoGrid}
            </div>
            <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
                <div class='section'>
                    <div class='title'>
                        <span>Details</span>
                    </div>
                    <div class='values'>
                        <span>Name: {$about->name}</span>
                        <span>Username: {$about->username}</span>
                        <span>Subscribers: {$about->numSubscribers}</span>
                        <span>Total Views: {$about->totalViews}</span>
                        <span>Signup: {$about->totalViews}</span>
                    </div>
                </div>
            </div>
        </div>
                
    </div>
HTML;

require_once(ROOT_DIR . "/src/application/views/layouts/main_layout.inc.php");