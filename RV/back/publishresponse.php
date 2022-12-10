<?php
    // This is the file for the updating and setting the finished results to published

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
        $points = $_POST['points'];
        $studentId = $_POST['studentId'];
        $examqid = $_POST['examqid'];
        $comments = $_POST['comments'];
        $examnum = $_POST['examnum'];

        $sql6 ="UPDATE Responses SET status='published',awardedpnts='$points',comments='$comments' WHERE studentId = '$studentId' and examQuestionId = '$examqid' and exam = '$examnum'";
        $connect->query($sql6);
    }
    $connect->close();
?>