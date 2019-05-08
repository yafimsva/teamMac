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
require_once('vendor/autoload.php');
require_once('model/database.php');
session_start();

//Create an instance of the Base class
$f3 = Base::instance();

//Turn of Fat-Free error reporting
$f3->set('DEBUG', 3);

$f3->route('GET|POST /home', function ($f3) {

    $db = new Database();
    $db->connect();

    $finalDates = array();
    $finalStudents = array();

    $f3->set("currentDate", date("Y-m-d"));

    $students = $db->getStudents();



    $f3->set('students', $students);


    if (isset($_POST['students'])) {
        $_SESSION['students'] = $_POST['students'];
        $array = $_POST['students'];

        foreach ($students as $student) {
            if ($db->getAttendanceCount($_POST['date'], $student['sid']) == 0) {
                $db->takeAttendance($_POST['date'], $student['sid'], false);
            }
        }
        foreach ($array as $sid) {
            // if ($db->getAttendance($_POST['date'], $sid, true) == 0) {
            $db->updateAttendance($sid, true, $_POST['date']);
            // }
        }

        $f3->reroute('home#');
    }

    $dates = $db->getDates();


    foreach ($dates as $date) {
        array_push($finalDates, $date['date']);
        array_push($finalStudents, $db->viewAttendanceByDate($date['date']));
    }

    //    print_r($finalStudents);


    $attendance = $db->viewAttendance();
    $f3->set('dates', $finalDates);
    $f3->set('allStudents', $finalStudents);

    $f3->set('attendances', $attendance);



    $template = new Template();
    echo $template->render('views/index.html');
});

$f3->route('GET|POST /', function () {
    $template = new Template();
    echo $template->render('views/login.html');
});

$f3->route('GET|POST /admin', function ($f3) {
    $db = new Database();
    $db->connect();

    $students = $db->getStudents();
    $f3->set('students', $students);

    if (isset($_POST['firstName'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];

        $db->insertStudent($firstName, $lastName, $dob, $email);
        $f3->reroute('admin#students');
    }




    $template = new Template();
    echo $template->render('views/admin.html');
});



//Run fat free
$f3->run();
