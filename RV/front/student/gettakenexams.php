<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]))) {
  die(header("Location: ../login.php"));
}

////////////////////////////////////////
$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midtakenexam.php';
$user = array( 'username' => $_SESSION["user"]["name"] );
$data_string = http_build_query($user); 

$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$decoded_json = json_decode($result, true);


echo $result;

////////////////////////////////////////

?>