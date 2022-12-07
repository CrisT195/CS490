<?php
require 'curl_util.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = array();
  $totalexams = array();
  $allresponses = array();
  $studentresponses = array();
  $url = "https://afsaccess4.njit.edu/~bjb38/CS490/midteacherpublish.php";
  $maxnumexams = 6;
  for ($examnum = 1; $examnum < $maxnumexams; $examnum++) {
    // Fields array to collect the previous information
    $totalexams = array();
    $allresponses = array();
    $studentresponses = array();
    $fields = [
      "examnum" => $examnum
    ];
    $response = call($url, $fields);
    $decoded_data = json_decode("$response", true);
    $responses = count($decoded_data);
    $studentpos = "studentId";
    // Return nothing
    if ($responses > 0) {
      // Take the first element as the baseline
      $studentname = $decoded_data[0][$studentpos];
      $studentresponses = [
        "$studentname" => []
      ];
      array_push($studentresponses[$studentname], $decoded_data[0]);
      for ($responsepos = 1; $responsepos < $responses; $responsepos++) {
        if ($decoded_data[$responsepos][$studentpos] == $studentname) {
          array_push($studentresponses[$studentname], $decoded_data[$responsepos]);
        } else {
          array_push($allresponses, $studentresponses);
          $studentname = $decoded_data[$responsepos][$studentpos];
          $studentresponses = [
            "$studentname" => []
          ];
          array_push($studentresponses[$studentname], $decoded_data[$responsepos]);
        }
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