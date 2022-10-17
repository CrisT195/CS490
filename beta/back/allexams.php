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

        $sql3 = "SELECT * FROM Exam";
        $result = $connect->query($sql3);
        if ($result->num_rows >= 3)
        {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            // This will determine the amount of exams,
            // since each exam is 3 questions
            $numquestions = 3;
            $exams = intdiv(count($rows), $numquestions);
            for ($i = 0; $i < $exams; $i++) {
                $exam = [
                "exam".($i+1)=> [$rows[$i],$rows[$i+1],$rows[$i+2]]
                ];
                array_push($fields, $exam);
            }
        }

        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>