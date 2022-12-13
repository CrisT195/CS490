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
        $constraint = $_POST['constraint'];
        $constraint = var_dump($constraint);
        echo $constraint;
$sql3 = "INSERT INTO Questionbank (question, difficulty, category, testcase1, testcase2, output1, output2, extras)
        VALUES
       ('$question', '$difficulty', '$category', '$testcase1', '$testcase2', '$output1', '$output2', $constraint)";
       echo $sql3;
       $connect->query($sql3);

    //    This is based on the assumption that output will always be defined
    $ti = 3;
    while (!is_null($_POST["testcase" . "$ti"]))
        {
            $testcase = $_POST["testcase" . "$ti"];
            $output = $_POST["output" . "$ti"];
            $sqlt ="UPDATE Questionbank SET testcase$ti='$testcase', output$ti='$output' WHERE question = '$question' AND difficulty = '$difficulty' AND category = '$category'";
            $connect->query($sqlt);
            ++$ti;
        }
    }
    $connect->close();
?>