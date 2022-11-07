<?php
    // This is the file for the starting teacher dashboard to check unpublished results

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

        $sql3 = "SELECT * FROM Responses as r, Exam as e, Questionbank as q WHERE r.examQuestionId = e.questionId and e.questionId = q.id and r.status = 'unpublished'";
        $result = $connect->query($sql3);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all();
            $fields = $rows;
        }

        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>