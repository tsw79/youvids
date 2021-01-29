<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/31/2019
 * Time: 23:52
 */
require_once("./vendor/redbean-orm/v5.3.1/rb.php");

// Connect to the DB
R::setup( 'mysql:host=localhost;dbname=youvids', 'youvids-usr', 'youvids123' );

// DB Insert
$newBook = R::dispense('book');
$newBook->title = 'Gifted Programmers';
$newBook->author = 'Charles Xavier';
$newBookId = R::store($newBook);

// DB Loading a record
$aBook = R::load( 'book', $newBookId );

// @TEST Categories
$categories = R::getAll("SELECT * FROM categories");

//@TEST find()
$findAll  = R::findAll( 'book', ' id > 4 ');

//@TEST Finder class
$f = new \RedBeanPHP\Finder(
    R::getToolBox()
);
$fResult = $f->find("book", " id = ? ", [6]);


?>
<h1>RedBeanPHP Test - <small>Integrating with PhpChassis framework</small></h1>

<?php var_dump($fResult); ?>

<?php /*var_dump($findAll); */?><!--

<?php /*var_dump($aBook); */?>

--><?php /*var_dump($categories); */?>
