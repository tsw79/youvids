<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/7/2019
 * Time: 16:14
 */
use Envms\FluentPDO\Query;

//require "./vendor/autoload.php";
//require_once("./vendor/envms/fluentpdo/src/Query.php");

$pdo = new PDO('mysql:host=localhost;dbname=youvids', 'youvids-usr', 'youvids123');
$fpdo = new Query($pdo);
debug($fpdo);

$q = $fpdo->from("users")
    ->where("id", 75)
    ->fetch();

//var_dump($pdoStatement->execute());
//var_dump($pdoStatement->getParameters());

var_dump($q);


//$query2 = $fpdo->from("videos")
////    ->where(["created >=" => "now() - INTERVAL 7 DAY"])
//    ->where("created >= now() - INTERVAL 7 DAY")
//    ->fetchAll();
//
//var_dump($query2);


$fpdo->update("videos")
    ->set(["views" => new \Envms\FluentPDO\Literal("views + 1")])
    ->where(["id" => 111])
    ->execute();

function debug(Query $fpdo) {

    $fpdo->debug = true;

//    $fpdo->debug = function ($q) {
//
//        $time  = sprintf('%0.3f', $q->getTotalTime() * 1000) . ' ms';
//        $rows  = ($q->getResult()) ? $q->getResult()->rowCount() : 0;
//        $query = $q->getQuery();
//        $msg = "# DB query ($time; rows = $rows) : $query";
//
//        $parameters = $q->getParameters();
//
//        if ($parameters) {
//            if (is_array($parameters)) {
//                $msg .= "\n# Parameters: '" . implode("', '", $parameters) . "'";
//            }
//            else {
//                $msg .= "\n# Parameters: '" . var_dump($parameters) . "'";
//            }
//        }
//
//        echo $msg."\n";
//    };
}






