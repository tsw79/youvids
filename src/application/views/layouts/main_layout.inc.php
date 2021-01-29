<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 2/28/2019
 * Time: 08:57
 */
list(
    $userProfileNav,
    $navAuthList
) = (new \youvids\application\controllers\LayoutController())->main();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>YouVids</title>
    <meta charset="utf-8">
    <meta name="description" content="Upload and share your own videos">
    <meta name="keywords" content="Videos, youvids, videos, websites">
    <meta name="author" content="tharwatsw">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="<?= WEB_ROOT ?>/images/favicon.png">
    <link rel="stylesheet" type="text/css" href="<?= WEB_ROOT ?>/css/bootstrap/v4.3.1/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= WEB_ROOT ?>/css/style.css">
</head>
<body>
<div id="pageContainer">
    <div id="mastHeadContainer">
        <button class="navShowHide">
            <i data-feather="menu"></i>
        </button>
        <a class="logoContainer" href="/index.php">
            <img src="<?php echo WEB_ROOT ?>/images/youvids_logo.png" title="YouVids logo" alt="YouVids.com">
        </a>
        <div class="searchBarContainer">
            <form action="/src/application/views/video/search.php" method="GET">
                <input type="text" class="searchBar" name="term" placeholder="Search">
                <button class="searchButton">
                    <i data-feather="search" stroke-width="1.7" color="#000" width="20px"></i>
                </button>
            </form>
        </div>
        <div class="rightIcons">
            <a href="/src/application/views/video/upload.php">
              <i data-feather="upload" stroke-width="1.5" color="#000"></i>
            </a>
            <?php echo $userProfileNav; ?>
        </div>
    </div>
    <!-- Left Nav items -->
    <div id="sideNavContainer" style="display: none;">
      <div style="margin: 10px 0;">
        <div class='navigationItems'>
            <div class='navigationItem'>
                <a href="/index.php">
                    <i data-feather="home" stroke-width="1.2" color="#000" width="20px"></i>
                    <span class="label">Home</span>
                </a>
            </div>
        </div>
        <div class='navigationItems'>
            <div class='navigationItem'>
                <a href="/src/application/views/video/trending.php">
                    <i data-feather="trending-up" stroke-width="1.2" color="#000" width="20px"></i>
                    <span class="label">Trending</span>
                </a>
            </div>
        </div>
        <div class='navigationItems'>
            <div class='navigationItem'>
                <a href="/src/application/views/user/subscriptions.php">
                    <i data-feather="bell" stroke-width="1.2" color="#000" width="20px"></i>
                    <span class="label">Subscriptions</span>
                </a>
            </div>
        </div>
        <div class='navigationItems'>
            <div class='navigationItem'>
                <a href="/src/application/views/video/liked.php">
                    <i data-feather="thumbs-up" stroke-width="1.2" color="#000" width="20px"></i>
                    <span class="label">Liked Videos</span>
                </a>
            </div>
        </div>
        <?php echo $navAuthList; ?>
      </div>
    </div>
    <div id="mainSectionContainer">
        <div id="mainContentContainer">
            <?= $content ?>
        </div>
    </div>
</div>
<?php require_once(ROOT_DIR . "/src/application/views/layouts/footer.inc.php"); ?>
</body>
</html>
