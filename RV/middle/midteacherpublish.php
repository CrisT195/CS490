<?php
require 'curl_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $examnum = $_POST['examnum'];

    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/checkteacherpublished.php";
    // Fields array to collect the previous information
    $fields = [
      "examnum" => $examnum
    ];
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>