<?php


if ($_SERVER['USER'] == 'yvainilo') {
    require_once('/home/yvainilo/config.php');
} else if ($_SERVER['USER'] == 'brandon') {
    require_once '/home/brandon/config.php';
} else if ($_SERVER['USER'] == 'nic') {
    require_once '/home/nic/config.php';
} else if ($_SERVER['USER'] == 'david') {
    require_once '/home/david/config.php';
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

    function insertStudent($first, $last, $dob, $parents_email)
    {
        global $dbh;

        $sql = "INSERT INTO students (first, last, dob, parents_email)
            VALUES(:fname, :lname, :dob, :parents_email);";

        $statement = $dbh->prepare($sql);

        $statement->bindValue(':fname', $first, PDO::PARAM_STR);
        $statement->bindValue(':lname', $last, PDO::PARAM_STR);
        $statement->bindValue(':dob', $dob, PDO::PARAM_STR);
        $statement->bindValue(':parents_email', $parents_email, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if (isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    public function getStudents()
    {
        global $dbh;

        $sql = "SELECT * FROM students";

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
        //        $statement->bindValue(':first', $first, PDO::PARAM_STR);
        //        $statement->bindValue(':last', $last, PDO::PARAM_STR);

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
}
