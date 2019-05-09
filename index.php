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

	if (!isset($_SESSION['teacherLogin'])) {
		$f3->reroute('/');
	}

	$db = new Database();
	$db->connect();

	$finalDates = array();
	$finalStudents = array();

	$f3->set("currentDate", date("Y-m-d"));

	$students = $db->getStudentsByID($_SESSION['classid']);



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

$f3->route('GET|POST /', function ($f3) {
	$db = new Database();
	$db->connect();

	if (isset($_SESSION['teacherLogin'])) {
		$f3->reroute('home');
	} else if (isset($_SESSION['adminLogin'])) {
		$f3->reroute('admin');
	}

	if (isset($_POST['username'], $_POST['password'])) {

		if (strtolower($_POST['username']) == "admin" && $_POST['password'] == "admin") {
			$_SESSION['adminLogin'] = true;

			$f3->reroute('admin');
		}

		$results = $db->checkLogin($_POST['username'], $_POST['password']);
		if (isset($results['teacherid'])) {
			$_SESSION['teacherLogin'] = true;
			$_SESSION['teacherid'] = $results['teacherid'];
			$_SESSION['name'] = $results['name'];
			$_SESSION['username'] = $results['username'];
			$_SESSION['classid'] = $results['classid'];
			$_SESSION['class'] = $results['className'];

			$f3->reroute('home');
		} else {
			$f3->reroute('/');
		}
		print_r($_SESSION);
	}


	$template = new Template();
	echo $template->render('views/login.html');
});

$f3->route('GET|POST /admin', function ($f3) {
	if (!isset($_SESSION['adminLogin'])) {
		$f3->reroute('/');
	}
	$db = new Database();
	$db->connect();

	$students = $db->getStudents();
	$teachers = $db->getTeachers();
	$classes = $db->getClasses();

	$f3->set('students', $students);
	$f3->set('teachers', $teachers);
	$f3->set('classes', $classes);



	// post student into database 
	if (isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['dob'])) {
		if ($_POST['studentClass'] != 0) {
			$firstName = $_POST['firstName'];
			$lastName = $_POST['lastName'];
			$email = $_POST['email'];
			$dob = $_POST['dob'];
			$studentClass = $_POST['studentClass'];

			$db->insertStudent($firstName, $lastName, $dob, $email, $studentClass);
			$f3->reroute('admin#students');
		} else {
			$f3->set("errors['studentClass']", "You did not pick a class for your student, student was not added");
		}
	}

	// post teacher into database 
	if (isset($_POST['teacherName'], $_POST['teacherUsername'], $_POST['password'], $_POST['confirmPassword'], $_POST['teacherClass'])) {
		if ($_POST['password'] == $_POST['confirmPassword']) {
			if ($_POST['teacherClass'] != 0) {
				$teacherName = $_POST['teacherName'];
				$teacherUsername = $_POST['teacherUsername'];
				$teacherPassword = ($_POST['password']);
				$teacherClass = $_POST['teacherClass'];
				$db->insertTeacher($teacherName, $teacherUsername, $teacherPassword, $teacherClass);
				$f3->reroute('admin#teachers');
			} else {
				$f3->set("errors['teacherClass']", "You did not pick a class for your teacher, student was not added");
			}
		} else {
			$f3->set("errors['nomatch']", "Passwords did not match, teacher was not added");
		}
	}

	//post classes into database
	if ($_POST['className']) {
		$db->insertClass($_POST['className']);
		$f3->reroute('admin#classes');
	}

	// post student into database 
	if (isset($_POST['updateFirstName'], $_POST['updateLastName'], $_POST['updateEmail'], $_POST['updateDob'], $_POST['updateStudentClass'])) {
		$updateFirstName = $_POST['updateFirstName'];
		$updateLastName = $_POST['updateLastName'];
		$updateEmail = $_POST['updateEmail'];
		$updateDob = $_POST['updateDob'];
		$updateStudentClass = $_POST['updateStudentClass'];
		$sid = $_POST['sid'];

		$db->updateStudent($sid, $updateFirstName, $updateLastName, $updateDob, $updateEmail, $updateStudentClass);
		$f3->reroute('admin#students');
	}

	//deletes a student
	if (isset($_POST['deleteStudent'])) {
		$db->deleteStudent($_POST['deleteStudent']);
		$f3->reroute('admin#students');
	}

	//updates a class name
	if (isset($_POST['updateClassName'], $_POST['updateClassNameID'])) {
		$newClassName = $_POST['updateClassName'];
		$updateClassNameID = $_POST['updateClassNameID'];
		$db->updateClass($newClassName, $updateClassNameID);
		$f3->reroute('admin#classes');
	}

	//updates a teacher 
	if (isset($_POST['updateTeacherName'], $_POST['updateTeacherUsername'])) {
		if ($_POST['updatePassword'] == $_POST['updateConfirmPassword']) {
			$updateTeacherName = $_POST['updateTeacherName'];
			$updateTeacherUsername = $_POST['updateTeacherUsername'];
			$updatePassword = $_POST['updatePassword'];
			$updateTeacherClass = $_POST['updateTeacherClass'];
			$updateTeacherId = $_POST['teacherid'];
			$db->updateTeacher($updateTeacherId, $updateTeacherName, $updateTeacherUsername, $updatePassword, $updateTeacherClass);

			if (isset($_POST['onStudentsPage'])) {
				$f3->reroute('admin#students');
			} else {
				$f3->reroute('admin#teachers');
			}
		} else {
			$f3->set("errors['updateNoMatch']", "Passwords did not match, teacher was not updated");
		}
	}

	//deletes a student
	if (isset($_POST['deleteTeacher'])) {
		$db->deleteTeacher($_POST['deleteTeacher']);

		if (isset($_POST['onStudentsPage'])) {
			$f3->reroute('admin#students');
		} else {
			$f3->reroute('admin#teachers');
		}
	}


	$template = new Template();
	echo $template->render('views/admin.html');
});

$f3->route('GET|POST /logout', function ($f3) {
	session_destroy();
	$f3->reroute('/');

	$template = new Template();
	echo $template->render('views/login.html');
});



//Run fat free
$f3->run();
