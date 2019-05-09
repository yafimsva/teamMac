<?php


if ($_SERVER['USER'] == 'yvainilo') {
    require_once('/home/yvainilo/config.php');
} else if ($_SERVER['USER'] == 'brandon') {
    require_once '/home/brandon/config.php';
} else if ($_SERVER['USER'] == 'nalexand') {
    require_once '/home/nalexand/config.php';
} else if ($_SERVER['USER'] == 'dkovalev') {
    require_once '/home/dkovalev/config.php';
}

/**
 * Class Database database class to insert and get information
 */
class Database
{

    public function connect()
    {
        try {
            //instantiate a database object
            $GLOBALS['dbh'] = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function insertStudent($first, $last, $dob, $parents_email, $studentClass)
    {
        global $dbh;

        $sql = "INSERT INTO students (first, last, dob, parents_email, classid)
            VALUES(:fname, :lname, :dob, :parents_email, :classid);";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':fname', $first, PDO::PARAM_STR);
        $statement->bindValue(':lname', $last, PDO::PARAM_STR);
        $statement->bindValue(':dob', $dob, PDO::PARAM_STR);
        $statement->bindValue(':parents_email', $parents_email, PDO::PARAM_STR);
        $statement->bindValue(':classid', $studentClass, PDO::PARAM_INT);


        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    public function getStudents()
    {
        global $dbh;

        $sql = "SELECT students.*, classes.className
        FROM students
        INNER JOIN classes
        ON students.classid = classes.classid 
        ORDER BY classid ASC, last ASC";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        //
        //        $posts = array();
        //
        //        foreach($results as $result) {
        //            $sid = $result['sid'];
        //            $first = $result['first'];
        //            $last = $result['last'];
        //        }

        return $results;
    }

    function takeAttendance($date, $sid, $present)
    {
        global $dbh;

        $sql = "INSERT INTO attendance (date, sid, present)
            VALUES(:date, :sid, :present);";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':sid', $sid, PDO::PARAM_STR);
        $statement->bindValue(':present', $present, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function updateAttendance($sid, $present, $date)
    {
        global $dbh;

        $sql = "UPDATE attendance SET present = :present WHERE sid = :sid AND date = :date;";


        $statement = $dbh->prepare($sql);

        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':sid', $sid, PDO::PARAM_STR);
        $statement->bindValue(':present', $present, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    public function viewAttendance()
    {
        global $dbh;

        $sql = "SELECT * FROM attendance";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function viewAttendanceByDate($date)
    {
        global $dbh;

        $sql = "SELECT students.first, students.last,
                attendance.date, attendance.sid, attendance.present
                FROM students
                INNER JOIN attendance
                ON students.sid = attendance.sid
                WHERE date = :date";

        $statement = $dbh->prepare($sql);
        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function getAttendanceCount($date, $sid)
    {
        global $dbh;

        $sql = "SELECT date, sid FROM attendance WHERE date = :date AND sid = :sid";

        $statement = $dbh->prepare($sql);
        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':sid', $sid, PDO::PARAM_STR);
        // $statement->bindValue(':present', $present, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->rowCount();

        return $results;
    }

    public function getDates()
    {
        global $dbh;

        $sql = "SELECT DISTINCT date FROM attendance ORDER BY date DESC";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    function insertTeacher($teacherName, $teacherUsername, $teacherPassword, $teacherClass)
    {
        global $dbh;

        $sql = "INSERT INTO teachers (name, username, password, classid)
            VALUES(:name, :username, :password, :classid);";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':name', $teacherName, PDO::PARAM_STR);
        $statement->bindValue(':username', $teacherUsername, PDO::PARAM_STR);
        $statement->bindValue(':password', $teacherPassword, PDO::PARAM_STR);
        $statement->bindValue(':classid', $teacherClass, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function insertClass($className)
    {
        global $dbh;

        $sql = "INSERT INTO classes (className)
            VALUES(:className);";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':className', $className, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function insertHelper($helperName, $classid)
    {
        global $dbh;

        $sql = "INSERT INTO helpers(name, classid) 
                VALUES (:helperName, :classid)";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':helperName', $helperName, PDO::PARAM_STR);
        $statement->bindValue(':classid', $classid, PDO::PARAM_STR);


        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    public function getHelpers()
    {
        global $dbh;

        $sql = "SELECT helpers.*, classes.className
        FROM helpers
        INNER JOIN classes
        ON helpers.classid = classes.classid 
        ORDER BY name ASC;";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }


    public function getTeachers()
    {
        global $dbh;

        $sql = "SELECT teachers.*, classes.className
        FROM teachers
        INNER JOIN classes
        ON teachers.classid = classes.classid 
        ORDER BY name ASC";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }



    public function getClasses()
    {
        global $dbh;

        $sql = "SELECT * FROM classes SORT ORDER BY className ASC";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    function updateStudent($sid, $first, $last, $dob, $parents_email, $studentClass)
    {
        global $dbh;

        $sql = "UPDATE students 
        SET first = :fname, last = :lname, dob = :dob, parents_email = :parents_email, classid = :classid 
        WHERE sid = :sid";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':fname', $first, PDO::PARAM_STR);
        $statement->bindValue(':lname', $last, PDO::PARAM_STR);
        $statement->bindValue(':dob', $dob, PDO::PARAM_STR);
        $statement->bindValue(':parents_email', $parents_email, PDO::PARAM_STR);
        $statement->bindValue(':classid', $studentClass, PDO::PARAM_INT);
        $statement->bindValue(':sid', $sid, PDO::PARAM_INT);



        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function updateTeacher($teacherid, $name, $username, $password, $classid)
    {
        global $dbh;

        $sql = "UPDATE teachers 
        SET name = :name, username = :username, password = :password, classid = :classid 
        WHERE teacherid = :teacherid";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_STR);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->bindValue(':password', $password, PDO::PARAM_STR);
        $statement->bindValue(':classid', $classid, PDO::PARAM_INT);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }


    function updateClass($className, $classid)
    {
        global $dbh;

        $sql = "UPDATE classes 
        SET className = :className
        WHERE classid = :classid";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':className', $className, PDO::PARAM_STR);
        $statement->bindValue(':classid', $classid, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function updateHelper($name, $classid, $helperid)
    {
        global $dbh;

        $sql = "UPDATE helpers 
                SET name = :name, classid = :classid
                WHERE helperid = :helperid";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':classid', $classid, PDO::PARAM_STR);
        $statement->bindValue(':helperid', $helperid, PDO::PARAM_STR);



        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function deleteHelper($helperid)
    {
        global $dbh;

        $sql = "DELETE FROM helpers WHERE helperid = :helperid";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':helperid', $helperid, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function deleteStudent($sid)
    {
        global $dbh;

        $sql = "DELETE FROM students WHERE sid = :sid";

        $statement = $dbh->prepare($sql);
        $statement->bindValue(':sid', $sid, PDO::PARAM_INT);
        $statement->execute();

        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    function deleteTeacher($teacherid)
    {
        global $dbh;

        $sql = "DELETE FROM teachers WHERE teacherid = :teacherid";

        $statement = $dbh->prepare($sql);
        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_INT);
        $statement->execute();

        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }
}
