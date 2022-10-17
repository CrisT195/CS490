<?php
    // This is the file for the getting all info on an exam joined with the student response

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
    $username = $_POST['username'];
    $questionid = $_POST['questionid'];
        // Establishing variables
        $fields = array();

        $sql3 = "SELECT * FROM Responses as r, Questionbank as q WHERE r.examQuestionId = q.id and r.examQuestionId = $questionid and r.studentId = $username";
        $result = $connect->query($sql3);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $fields = $rows;
        }

        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>