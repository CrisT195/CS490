<?php
require 'curl_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $points = $_POST['points'];
    $studentId = $_POST['studentId'];
    $examqid = $_POST['examqid'];
    $comments = $_POST['comments'];
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/publishresponse.php";
    // Fields array to collect the previous information
    $fields = [
      "points" =>         $points,
      "studentId" =>         $studentId,
      "examqid" =>         $examqid,
      "comments" =>         $comments
    ];
    $response = call($url, $fields);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>