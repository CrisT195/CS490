<?php
require 'curl_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $question = $_POST['question'];
    $difficulty = $_POST['difficulty'];
    $category = $_POST['category'];
    $testcase1 = $_POST['testcase1'];
    $testcase2 = $_POST['testcase2'];
    $testcase3 = $_POST['testcase3'];
    $testcase4 = $_POST['testcase4'];
    $testcase5 = $_POST['testcase5'];
    $output1 = $_POST['output1'];
    $output2 = $_POST['output2'];
    $output3 = $_POST['output3'];
    $output4 = $_POST['output4'];
    $output5 = $_POST['output5'];
    $constraint = $_POST['constraint'];
 
    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/question.php";
    // Fields array to collect the previous information
    $fields = [
        "question" => $question,
        "difficulty" => $difficulty,
        "category" => $category,
        "testcase1" => $testcase1,
        "testcase2" => $testcase2,
        "testcase3" => $testcase3,
        "testcase4" => $testcase4,
        "testcase5" => $testcase5,
        "output1" => $output1,
        "output2" => $output2,
        "output3" => $output3,
        "output4" => $output4,
        "output5" => $output5,
        "constraint" => $constraint
    ];
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>