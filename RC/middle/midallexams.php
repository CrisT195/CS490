<?php
require 'curl_util.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/allexams.php";
    // Fields array to collect the previous information
    $fields = [];
    
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>