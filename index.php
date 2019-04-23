<?php

/*
 * Yafim Vainilovich
 * April 23, 2019
 * 355/teamMac/index.php
 */

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require autoload
require_once ('vendor/autoload.php');

//Create an instance of the Base class
$f3 = Base::instance();

//Turn of Fat-Free error reporting
$f3->set('DEBUG', 3);

$f3->route('GET|POST /home', function() {

    $template = new Template();
    echo $template->render('views/index.html');
});

$f3->route('GET|POST /', function() {

    $template = new Template();
    echo $template->render('views/login.html');
});



//Run fat free
$f3->run();
