<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/2/2019
 * Time: 12:00
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PhpChassis-DDD Test</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #ddd;
        }

        td {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            echo $content;

            if (isset($_GET) && !empty($_GET)) {
                echo "<h3>GET Request</h3>";
                var_dump($_GET);
            }
            elseif (isset($_POST) && !empty($_POST)) {
                echo "<h3>POST Request</h3>";
                var_dump($_POST);
            }
        ?>
    </div>
</body>
</html>
