<?php
require 'curl_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $username = $_POST['username'];
    // $username = 'student500';
    $result = array();
    $totalexams = array();
    $allresponses = array();
    $studentresponses = array();
    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/midstudentpublish.php";
    $maxnumexams = 5;
    for ($examnum = 1; $examnum < $maxnumexams; $examnum++) {
      // Fields array to collect the previous information
      $totalexams = array();
      $allresponses = array();
      $studentresponses = array();
      $fields = [
        "username" => $username,
        "examnum" => $examnum
      ];
      $response = call($url, $fields);
      $decoded_data = json_decode("$response", true);
      $responses = count($decoded_data);
      // Return nothing
      if ($responses > 0) {
        // Take the first element as the baseline
        $studentresponses = [
          "$username" => []
        ];
        for ($responsepos = 0; $responsepos < $responses; $responsepos++) {
            array_push($studentresponses[$username], $decoded_data[$responsepos]);
          }
      array_push($allresponses, $studentresponses);
      $totalexams = [
        "Exam".$examnum => $allresponses
      ];
      array_push($result, $totalexams);
      }
    }
    $myJSON = json_encode($result);
    echo $myJSON;
  }
?>