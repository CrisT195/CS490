<?php
    // This is the file for the inserting into response

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
        // Passed values
        $studentId = $_POST['username'];
        $examqid = $_POST['examqid'];
        $answer = $_POST['answer'];


        $sql3 = "INSERT INTO Responses (status, studentId, examQuestionId, answer)
        VALUES
       ('unpublished','$studentId', '$examqid', '$answer')";
       $connect->query($sql3);
    }
    $connect->close();
?>