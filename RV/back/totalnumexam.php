<?php
    // This is the file getting the total number of unique exams

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
        $fields = array();
        $sql = "SELECT COUNT(DISTINCT examnum) from Exam";
        $result = $connect->query($sql);
        $row = $result->fetch_all();
        // Access the first element of the array, a number
        // SQL will return 0 automatically
        $fields = $row[0];

        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>