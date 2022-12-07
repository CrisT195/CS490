<?php
require 'curl_util.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $questions = $_POST['questions'];
  // $questions = [[16,"30"],[19,"30"],[20,"40"]];

  // Get the number of exams
  $url = "https://afsaccess4.njit.edu/~nk553/CS490/totalnumexam.php";
  // Fields array to collect the previous information
  $fields = array();

  $response = call($url, $fields);

  // Send back the full response - json as opposed to a string
  $data = json_decode($response);
  // Increase the examnum
  $examnum = intval($data[0]) + 1;

  // Make a call to the backend to get the number of exams
  // Increment by 1, send that examnum

  $url = "https://afsaccess4.njit.edu/~bjb38/CS490/insertexam.php";

  $fields = [
    "questions" => $questions,
    "examnum" => $examnum
  ];
  $response = call($url, $fields);

  // Send back the full response - json as opposed to a string
  echo $response;
}
?>