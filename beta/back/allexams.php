<?php
    // This is the file for the getting all exams

    // Establishing connection to SQL server
    $servername = "sql1.njit.edu";
    $username = "nk553";
    $password = "KanyeWest300*";
    $db = "nk553";
    $connect = new mysqli($servername, $username, $password, $db);
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Establishing variables
        $fields = array();
        $maxnumexams = 5;
        for ($examnum = 1; $examnum < $maxnumexams; $examnum++) {
        $sql3 = "SELECT * FROM Exam WHERE examnum = '$examnum'";
        $result = $connect->query($sql3);
        if ($result->num_rows >= 3)
        {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            // This will determine the amount of exams
            $numrows = count($rows);
            $examquestions = [
                "exam".($examnum) => []
            ];
            $exam = array();
            for ($i = 0; $i < $numrows; $i++) {
                array_push($exam, $rows[$i]);
            }
            array_push($examquestions["exam".($examnum)], $exam);
            array_push($fields, $examquestions);
        }
    }
        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>