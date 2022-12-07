<?php
require 'curl_util.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $username = $_POST['username'];
    $examqid = $_POST['examqid'];
    $answer = $_POST['answer'];
    $examnum = $_POST['examnum'];
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/response.php";
    // Fields array to collect the previous information
    $fields = [
      "username" => $username,
      "examqid" => $examqid,
      "examnum" => $examnum,
      "answer" => $answer
    ];
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>