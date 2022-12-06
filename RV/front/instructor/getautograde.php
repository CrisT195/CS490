<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
 }

 $json = file_get_contents('php://input');
 $data = json_decode($json);
 // echo $json;
 
 // $placeholder = '{"response":[["unpublished","student300","20","def (hi):\n\treturn 0",null,null,null],["unpublished","student300","16","def (hi):\n\treturn 0",null,null,null],["unpublished","student300","21","def (hi):\n\treturn 0",null,null,null]]}';

 $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/sendforgrade.php';
 $data_string = http_build_query($data); ////json_decode($placeholder));
 $ch = curl_init($url); 
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 curl_close($ch);
 // $send = array('response' => $result);
 // echo json_encode($send);
 echo $result;
 // [["unpublished","student300","16","def hi():\n\treturn 0",null,null,null], ... ]
?>