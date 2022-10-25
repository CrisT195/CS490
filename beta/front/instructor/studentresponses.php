<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}


$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midteacherpublish.php';
$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);


// echo json_encode($result);
echo $result;
// [
//   ["4","unpublished","student300","20","def (hi):\n\treturn 0",null,null,null],
//   ["5","unpublished","student300","16","def (hi):\n\treturn 0",null,null,null],
//   ["6","unpublished","student300","21","def (hi):\n\treturn 0",null,null,null],
//   ["7","unpublished","student400","16","def (hi):\n\treturn 0",null,null,null],
//   ["8","unpublished","student400","21","def (hi):\n\treturn 0",null,null,null],
//   ["9","unpublished","student400","20","def (hi):\n\treturn 0",null,null,null]
// ]


?>
