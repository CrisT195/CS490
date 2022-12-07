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
    $questionid = $_POST['questionId'];
    $examnum = $_POST['examnum'];
        // Establishing variables
        $fields = array();

        $sql3 = "SELECT * FROM Responses as r JOIN Exam as e ON r.exam = e.examnum and r.examQuestionId = e.questionId JOIN Questionbank as q ON e.questionId = q.id WHERE r.examQuestionId = $questionid and r.studentId = '$username' and r.exam = $examnum";
        $result = $connect->query($sql3);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();
            $fields = $rows;
        }
        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>