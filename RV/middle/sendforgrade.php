<?php
require 'curl_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $response = $_POST['response'];
    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/grade.php";
    // Fields array to collect the previous information
    $fields = [
        "response" => $response
    ];
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>