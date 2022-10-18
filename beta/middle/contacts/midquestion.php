<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $question = $_POST['question'];
    $difficulty = $_POST['difficulty'];
    $category = $_POST['category'];
    $testcase1 = $_POST['testcase1'];
    $testcase2 = $_POST['testcase2'];
    $output1 = $_POST['output1'];
    $output2 = $_POST['output2'];
    // url to change later
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/question.php";
    // Fields array to collect the previous information
    $fields = [
        "question" => $question,
        "difficulty" => $difficulty,
        "category" => $category,
        "testcase1" => $testcase1,
        "testcase2" => $testcase2,
        "output1" => $output1,
        "output2" => $output2
    ];
    // Builds into a string from the array
    $fields_string = http_build_query($fields);
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // grab URL and pass it to the browser
    $response = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>