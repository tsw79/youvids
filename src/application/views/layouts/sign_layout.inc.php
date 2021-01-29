<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/31/2019
 * Time: 09:41
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>YouVids</title>
    <meta charset="utf-8">
    <meta name="description" content="Upload and share your own videos">
    <meta name="keywords" content="Videos, youvids, videos, websites">
    <meta name="author" content="Tharwat Sakeldien">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= WEB_ROOT ?>/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= WEB_ROOT ?>/css/bootstrap/v4.3.1/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= WEB_ROOT ?>/css/style.css">
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/jquery/v3.1.1/jquery-3.1.1.min.js"></script>

    <!-- TODO Needs to point to local file -->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>-->

    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/bootstrap/v4.3.1/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= WEB_ROOT ?>/js/common.js"></script>
</head>
<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <img src="<?= WEB_ROOT ?>/images/youvids_logo.png" title="YouVids logo" alt="YouVids.com">
                <h3><?php echo $title; ?></h3>
                <span>to continue to YouVids</span>
            </div>
            <div class="loginForm">
                
            <?= $content ?>

</body>
</html>