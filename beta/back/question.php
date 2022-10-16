<?php
    // This is the file for the inserting into the question bank

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
        $question = $_POST['question'];
        $difficulty = $_POST['difficulty'];
        $category = $_POST['category'];
        $testcase1 = $_POST['testcase1'];
        $testcase2 = $_POST['testcase2'];
        $output1 = $_POST['output1'];
        $output2 = $_POST['output2'];


        $sql3 = "INSERT INTO Questionbank (question, difficulty, category, testcase1, testcase2, output1, output2)
        VALUES
       ('$question', '$difficulty', '$category', '$testcase1', '$testcase2', '$output1', '$output2')";
       $connect->query($sql3);
    }
    $connect->close();
?>