<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]))) {
  die(header("Location: ../login.php"));
}

$obj = array($_SESSION["user"]["name"]);
$json = json_encode($obj);
echo $json;


?>