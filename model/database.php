<?php
if ($_SERVER['USER'] == 'yvainilo') {
    require_once('/home/yvainilo/config.php');
} else if ($_SERVER['USER'] == 'bskargre') {
    require_once '/home/bskargre/config.php';
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
    public function getStudentsByID($classid)
    {
        global $dbh;
        $sql = "SELECT * FROM `students` WHERE classid = :classid ORDER BY last ASC;";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':classid', $classid, PDO::PARAM_STR);
        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    public function viewAttendance($classid)
    {
        global $dbh;
        $sql = "SELECT students.first, students.last,
        attendance.date, attendance.sid, attendance.present, classes.classid
        FROM students
        INNER JOIN attendance
        ON students.sid = attendance.sid
        INNER JOIN classes 
        ON students.classid = classes.classid
        WHERE students.classid = :classid;";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':classid', $classid, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function viewSchedule()
    {
        global $dbh;
        $sql = "SELECT teachers.name,
        schedule.date, schedule.teacherid, schedule.scheduled, classes.classid
        FROM teachers
        INNER JOIN schedule
        ON teachers.teacherid = schedule.teacherid
        INNER JOIN classes 
        ON teachers.classid = classes.classid;";
        $statement = $dbh->prepare($sql);
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
        $sql = "SELECT DISTINCT date, DATE_FORMAT(date,'%b %d, %Y') as niceDate FROM attendance ORDER BY date DESC";
        $statement = $dbh->prepare($sql);
        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function getDatesForTeachers($start, $end)
    {
        global $dbh;
        $sql = "SELECT *, DATE_FORMAT(date,'%b %d, %Y') as niceDate, DATE_FORMAT(date,'%a') as dayName, DATE_FORMAT(date,'%M') as monthName from 
        (select adddate('1970-01-01',t4*10000 + t3*1000 + t2*100 + t1*10 + t0) date from
        (select 0 t0 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
        (select 0 t1 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
        (select 0 t2 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
        (select 0 t3 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
        (select 0 t4 union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
        where date between :start and :end";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':start', $start, PDO::PARAM_STR);
        $statement->bindValue(':end', $end, PDO::PARAM_STR);

        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function setSchedule($date, $teacherid, $scheduled)
    {
        global $dbh;
        $sql = "INSERT INTO schedule (date, teacherid, scheduled) VALUES (:date, :teacherid, :scheduled);";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_STR);
        $statement->bindValue(':scheduled', $scheduled, PDO::PARAM_STR);

        $statement->execute();
    }

    public function updateSchedule($date, $teacherid, $scheduled)
    {
        global $dbh;

        $sql = "UPDATE schedule SET scheduled = :scheduled WHERE teacherid = :teacherid AND date = :date;";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_STR);
        $statement->bindValue(':scheduled', $scheduled, PDO::PARAM_STR);

        $statement->execute();
    }

    function insertTeacher($teacherName, $teacherUsername, $teacherPassword, $teacherClass, $endDate)
    {
        global $dbh;
        $sql = "INSERT INTO teachers (name, username, password, classid, endDate)
            VALUES(:name, :username, :password, :classid, :endDate);";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':name', $teacherName, PDO::PARAM_STR);
        $statement->bindValue(':username', $teacherUsername, PDO::PARAM_STR);
        $statement->bindValue(':password', $teacherPassword, PDO::PARAM_STR);
        $statement->bindValue(':classid', $teacherClass, PDO::PARAM_STR);
        $statement->bindValue(':endDate', $endDate, PDO::PARAM_STR);
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
        $sql = "SELECT DATEDIFF(teachers.endDate ,CURRENT_DATE()) daysLeft, teachers.*, classes.className
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
    function updateTeacher($teacherid, $name, $username, $password, $classid, $endDate)
    {
        global $dbh;
        $sql = "UPDATE teachers 
        SET name = :name, username = :username, password = :password, classid = :classid, endDate = :endDate 
        WHERE teacherid = :teacherid";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_STR);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->bindValue(':password', $password, PDO::PARAM_STR);
        $statement->bindValue(':endDate', $endDate, PDO::PARAM_STR);
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
    public function checkLogin($username, $password)
    {
        global $dbh;
        $sql = "SELECT DATEDIFF(teachers.endDate ,CURRENT_DATE()) daysLeft, teachers.*, classes.className FROM teachers INNER JOIN classes
        ON teachers.classid = classes.classid WHERE username = :username AND password = :password;";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->bindValue(':password', $password, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetch(PDO::FETCH_ASSOC);
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        return $results;
    }

    public function checkIfAttedanceTaken($date, $sid)
    {
        global $dbh;
        $sql = "SELECT * FROM attendance WHERE date = :date AND sid = :sid";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':sid', $sid, PDO::PARAM_STR);
        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        print_r($results);
        return $results;
    }

    public function checkIfScheduleSet($date, $teacherid)
    {
        global $dbh;
        $sql = "SELECT * FROM schedule WHERE date = :date AND teacherid = :teacherid";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':date', $date, PDO::PARAM_STR);
        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_STR);
        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function getMySchedule($teacherid)
    {

        global $dbh;
        $sql = "SELECT * FROM schedule where teacherid = :teacherid ORDER BY date DESC LIMIT 15";
        $statement = $dbh->prepare($sql);
        $statement->bindValue(':teacherid', $teacherid, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function getScheduleDates()
    {
        global $dbh;
        $sql = "SELECT DISTINCT date FROM schedule ORDER BY date DESC";
        $statement = $dbh->prepare($sql);
        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }


}
