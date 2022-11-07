<?php


$loader = require 'src/vendor/autoload.php';
$loader->addPsr4('iutnc\\deefy\\', 'src\classes');


use netvod\dispatch\Dispatcher;

session_start();
$_GET['action'] = $_GET['action'] ?? "";
$dispatcher = new Dispatcher($_GET['action']);
$dispatcher->run();
