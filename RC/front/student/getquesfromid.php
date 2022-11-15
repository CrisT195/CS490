<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "student")) {
  die(header("Location: ../login.php"));
}

$json = file_get_contents('php://input');
$data = json_decode($json);

$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midallquestions.php';
$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
// echo $result;
// ex: { "questions" : [ {"id":0, "description":"Given a string name, e.g. 'Bob', create a function named 'hello_name' that returns a greeting of the form 'Hello Bob!'." , "difficulty":"easy", "category":"strings" }, {"id":1, "description":"question 2" , "difficulty":"medium", "category":"loops" }, {"id":2, "description":"question 3" , "difficulty":"hard", "category":"arithmetic" } ] }
$decoded_json = json_decode($result, true);
$return = array();
foreach ($decoded_json as $question) {
  if (in_array($question['id'], $data)) {
    // $return += [$question['id'] => $question];
    $return += array($question['id'] => $question);
  }
}
echo json_encode($return);
?>