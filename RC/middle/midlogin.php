<?php
require 'curl_util.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $username = $_POST['username'];
    $password = $_POST['password'];
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/loginbackend.php";
    // Fields array to collect the previous information
    $fields = [
        "username" => $username,
        "password" => $password
    ];
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>