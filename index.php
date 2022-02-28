<?php
require_once 'vendor/autoload.php';
use Nosfair\Blogpost\Service\Main;

//Rename __DIR__
define('ROOT', dirname(__DIR__));

//Instance of Main
$app= new Main;
//Start the application
$app->start();