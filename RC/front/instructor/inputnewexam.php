<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}


$json = file_get_contents('php://input');

$data = json_decode($json);
// echo $json;
// echo $data;
// print_r()

$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midexam.php';
// TODO: Fix sending exam to middle
$data_string = http_build_query($data); //$json
$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
// $decoded_json = json_decode($result, true);
echo $result;

?>