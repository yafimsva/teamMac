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
	print_r($_POST);
	if (!isset($_SESSION['teacherLogin'])) {
		$f3->reroute('/');
	}
	$db = new Database();
	$db->connect();
	$f3->set("currentDate", date("Y-m-d"));
	$students = $db->getStudentsByID($_SESSION['classid']);
	$dates = $db->getDates();
	$attendances = $db->viewAttendance($_SESSION['classid']);
	$mySchedule = $db->getMySchedule($_SESSION['teacherid']);
	$getHelpers = $db->getHelpersForClass(($_SESSION['classid']));
	$f3->set('students', $students);
	$f3->set('datesArray', $dates);
	$f3->set('attendances', $attendances);
	$f3->set('mySchedule', $mySchedule);
	$f3->set('helpers', $getHelpers);


	if(isset($_POST['updateAttendance']))
	{
		$db->teacherUpdateAttendanceToAbsent($_SESSION['classid'], $_POST['date']);

		$classid = $_SESSION['classid'];
		$date =$_POST['date'];

		foreach ($_POST['updateAttendance'] as $sid) {
			$db->teacherUpdateAttendance($_SESSION['classid'], $_POST['date'], $sid);
			// $taken = $db->checkIfAttedanceTaken($_POST['date'], $sid);
			// if(sizeof($taken) == 0)
			// {
			// 	$db->takeAttendance($_POST['date'], $sid);
			// }
			// else
			// {
			// 	echo("<script>alert('ERROR: Duplicate attendance entires')</script>");
			// }
		}

		$f3->reroute('home#calendar');
	}

	if (isset($_POST['attendance'])) {
		$duplicate = false;

		foreach ($_POST['attendance'] as $sid) {
			$check = $db->checkIfAttedanceTaken($_POST['date'], $sid);
			if (sizeof($check) != 0) {
				$duplicate = true;
				break;
			}
		}
		if (!$duplicate) {
			foreach ($students as $student) {
				$db->takeAttendance($_POST['date'], $student['sid'], 0);
			}
			foreach ($_POST['attendance'] as $sid) {
				$db->updateAttendance($sid, 1, $_POST['date']);
				// $taken = $db->checkIfAttedanceTaken($_POST['date'], $sid);
				// if(sizeof($taken) == 0)
				// {
				// 	$db->takeAttendance($_POST['date'], $sid);
				// }
				// else
				// {
				// 	echo("<script>alert('ERROR: Duplicate attendance entires')</script>");
				// }
			}
			$f3->reroute('home');
		} else {
			$f3->reroute('home#calendar');
		}
	}

	
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

		$usernameExists = $db->usernameExists($_POST['username']);

		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = $_POST['password'];

		if($usernameExists)
		{
			$results = $db->checkLogin($_POST['username'], $_POST['password']);

			if (isset($results['teacherid'])) {
				$_SESSION['teacherLogin'] = true;
				$_SESSION['teacherid'] = $results['teacherid'];
				$_SESSION['name'] = $results['name'];
				$_SESSION['username'] = $results['username'];
				$_SESSION['classid'] = $results['classid'];
				$_SESSION['class'] = $results['className'];
				$_SESSION['daysLeft'] = $results['daysLeft'];
				$f3->reroute('home');
			} else {
				$_SESSION['usernameError'] = "valid";
				$_SESSION['loginError'] = "invalid";
	
				$f3->reroute('/');
			}
		}
		else
		{
			$_SESSION['loginError'] = "valid";
			$_SESSION['usernameError'] = "invalid";
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
	
	$first_day_this_month = date('Y-m-01');
	$this_month = date('m');
	$last_day_next_month  = date('Y-m-t', strtotime(" +1 months"));
	$next_month = date('m', strtotime(" +1 months"));
	$this_month_name = date("F", mktime(0, 0, 0, $this_month, 10));
	$next_month_name = date("F", mktime(0, 0, 0, $next_month, 10));


	$datesForTeachers = $db->getDatesForTeachers($first_day_this_month, $last_day_next_month);
	$students = $db->getStudents();
	$teachers = $db->getTeachers();
	$classes = $db->getClasses();
	$helpers = $db->getHelpers();
	$scheduleDates = $db->getScheduleDates();
	$viewSchedules = $db->viewSchedule();
	$f3->set('students', $students);
	$f3->set('teachers', $teachers);
	$f3->set('classes', $classes);
	$f3->set('helpers', $helpers);
	$f3->set('scheduleDates', $scheduleDates);
	$f3->set('viewSchedules', $viewSchedules);
	$f3->set('datesForTeachers', $datesForTeachers);
	$f3->set('this_month_name', $this_month_name);
	$f3->set('next_month_name', $next_month_name);



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
				if ($_POST['endDate'] != '') {
					$teacherEndDate = $_POST['endDate'];
				} else {
					$teacherEndDate = NULL;
				}
				$teacherName = $_POST['teacherName'];
				$teacherUsername = $_POST['teacherUsername'];
				$teacherPassword = ($_POST['password']);
				$teacherClass = $_POST['teacherClass'];
				$db->insertTeacher($teacherName, $teacherUsername, $teacherPassword, $teacherClass, $teacherEndDate);
				$f3->reroute('admin#teachers');
			} else {
				$f3->set("errors['teacherClass']", "You did not pick a class for your teacher, teacher was not added");
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
			if ($_POST['endDateUpdate'] != '') {
				$updateTeacherEndDate = $_POST['endDateUpdate'];
			} else {
				$updateTeacherEndDate = NULL;
			}
			$updateTeacherName = $_POST['updateTeacherName'];
			$updateTeacherUsername = $_POST['updateTeacherUsername'];
			$updatePassword = $_POST['updatePassword'];
			$updateTeacherClass = $_POST['updateTeacherClass'];
			$updateTeacherId = $_POST['teacherid'];
			$db->updateTeacher($updateTeacherId, $updateTeacherName, $updateTeacherUsername, $updatePassword, $updateTeacherClass, $updateTeacherEndDate);
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
	//insert a helper
	if (isset($_POST['helperName'], $_POST['helperClass'])) {
		if ($_POST['helperClass'] != 0) {
			$db->insertHelper($_POST['helperName'], $_POST['helperClass']);
			if (isset($_POST['onStudentsPage'])) {
				$f3->reroute('admin#students');
			} else {
				$f3->reroute('admin#teachers');
			}
		} else {
			$f3->set("errors['noClassHelper']", "You did not pick a class for your helper, helper was not added");
		}
	}
	//updates a helper 
	if (isset($_POST['updateHelperName'], $_POST['updateHelperClass'])) {
		$updateHelperName = $_POST['updateHelperName'];
		$updateHelperClass = $_POST['updateHelperClass'];
		$helperid = $_POST['helperid'];
		$db->updateHelper($updateHelperName, $updateHelperClass, $helperid);
		if (isset($_POST['onStudentsPage'])) {
			$f3->reroute('admin#students');
		} else {
			$f3->reroute('admin#teachers');
		}
	}
	//deletes a helper
	if (isset($_POST['deleteHelper'])) {
		$db->deleteHelper($_POST['deleteHelper']);
		if (isset($_POST['onStudentsPage'])) {
			$f3->reroute('admin#students');
		} else {
			$f3->reroute('admin#teachers');
		}
	}

	//Set schedule
	if (isset($_POST['schedule'])) {
			$duplicate = false;

			foreach ($_POST['schedule'] as $teacherid) {
				$check = $db->checkIfScheduleSet($_POST['scheduleDate'], $teacherid);
				if (sizeof($check) != 0) {
					$duplicate = true;
					break;
				}
			}
			if (!$duplicate) {
				foreach ($teachers as $teacher) {
					$db->setSchedule($_POST['scheduleDate'], $teacher['teacherid'], 0);
				}
				foreach ($_POST['schedule'] as $teacherid) {
					$db->updateSchedule($_POST['scheduleDate'], $teacherid, 1);
				}
				$f3->reroute('admin#schedule');
			} else {
				$f3->reroute('admin#schedule');
			}
	}

	//delete schedule for one date
	if (isset($_POST['deleteSchedule']))
	{
		$db->deleteScheduleForOneDate($_POST['deleteSchedule']);
		$f3->reroute('admin#schedule');
	}

	$template = new Template();
	echo $template->render('views/admin.html');
});


$f3->route('GET|POST /file', function ($f3) {
	$template = new Template();
	echo $template->render('files.html');
});

$f3->route('GET|POST /upload', function($f3) {
    $target_dir = $_POST['location'] . '/';
    $currentLocation = str_replace(' ', '%20', $target_dir);
    $currentLocation = str_replace('/', '%2F', $currentLocation);

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if(isset($_POST['submit'])) {
//        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//        if($check !== false) {
//            echo "File is an image - " . $check["mime"] . ".";
//            $uploadOk = 1;
//        }
//        else {
//            echo "File is not an image.";
//            $uploadOk = 0;
//        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
//        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//            && $imageFileType != "gif" ) {
//            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//            $uploadOk = 0;
//        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $f3->reroute('admin#files');
//                $f3->reroute('admin#files' . $currentLocation);
            } else {
                echo "<h1>SORRY</h1>";
            }
        }
    }
});

$f3->route('GET|POST /add_directory', function ($f3) {
    $target_dir = $_POST['location'] . '/';
    $currentLocation = str_replace(' ', '%20', $target_dir);
    $currentLocation = str_replace('/', '%2F', $currentLocation);

    mkdir($target_dir . $_POST['folder_name']);
    $f3->reroute('admin#files');
//    $f3->reroute('admin#' . $currentLocation);
});

$f3->route('GET|POST /logout', function ($f3) {
	session_destroy();
	$f3->reroute('/');
	$template = new Template();
	echo $template->reroute('admin#files');
});

//Run fat free
$f3->run();
