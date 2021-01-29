<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/26/2019
 * Time: 06:44
 *
 *              "maximebf/debugbar" : ">=1.10.0"
 */
require ROOT_DIR . "/vendor/autoload.php";

$debugbar = new \DebugBar\StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer("/udemy/youvids/vendor/maximebf/debugbar/src/DebugBar/Resources");

//Add some messages
$debugbar['messages']->addMessage('PHP 7 by Packt');
$debugbar['messages']->addMessage('Written by a Hero');
?>
<html>
    <head>
        <!--<script type="text/javascript" src="<?php /*echo WEB_ROOT; */?>/js/jquery-3.3.1.min.js"></script>-->
        <?php echo $debugbarRenderer->renderHead(); ?>
    </head>
    <title>Debug Bar</title>
    <body>
        <h1>Welcome to Debug Bar</h1>
        <!-- display debug bar here -->
        <?php echo $debugbarRenderer->render();  ?>
    </body>
</html>