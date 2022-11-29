<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "student")) {
  die(header("Location: ../login.php"));
 }

 $json = file_get_contents('php://input');
 $data = json_decode($json);
 $question = array("username" => $_SESSION["user"]["name"], "examqid" => $data->examqid, "answer" => $data->answer, "examnum" => $data->examnum);
 // echo json_encode($question);
 // echo json_encode($json);

 $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midresponse.php';
 $data_string = http_build_query($question); 
 $ch = curl_init($url); 
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 curl_close($ch);
 // echo $result;
 echo json_encode($question);
 // [[username, examqid, answer], ...]
?>
