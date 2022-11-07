<?php


$loader = require 'vendor/autoload.php';
$loader->addPsr4('netvod\\', 'classes');


use netvod\dispatch\Dispatcher;

session_start();
$_GET['action'] = $_GET['action'] ?? "";
$dispatcher = new Dispatcher($_GET['action']);
$dispatcher->run();
