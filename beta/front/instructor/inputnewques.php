<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
 }


$json = file_get_contents('php://input');

// decode the json data
$data = json_decode($json);

$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midquestion.php';
$question = array("question" => $data->question, "difficulty" => $data->difficulty, "category" => $data->category, "testcase1" => $data->testcase1, "output1" => $data->output1, "testcase2" => $data->testcase2, "output2" => $data->output2);
// echo json_encode($var);

$data_string = http_build_query($question); 
$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
echo $result

// {"question":"test","difficulty":"easy","category":"conditionals","testcase1":"test","output1":"test","testcase2":"testt","output2":"est"}

?>
