<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 2/28/2019
 * Time: 23:54
 */
use youvids\file\ {VideoUploadData, VideoProcessor};

//require_once(ROOT_DIR . "/views/layouts/main_layout.inc.php");

var_dump($_FILES);

if(!isset($_POST["uploadBtn"])) {
    echo "No data found.";
    exit();
}





//*** Step 1) Create file upload data

$videoUploadData = new VideoUploadData($_FILES, $_POST);

//*** Step 2) Upload and process video data

$videoProcessor = new VideoProcessor();
$successful = $videoProcessor->process($videoUploadData);

//*** Step 3) Check if upload was successful

if($successful) {
    echo "Upload successful.";
}


//var_dump($_POST); exit(0);

?>