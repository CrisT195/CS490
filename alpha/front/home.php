<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]))) {
  die(header("Location: login.php"));
 }
 // var_dump($_SESSION["user"]);
 // echo $_SESSION["user"]["name"];
?>

<div style="text-align: center;">
 <?php if ($_SESSION["user"]["role"] == "teacher"): ?>
  <h1>Teacher Dashboard</h1>
 <?php elseif ($_SESSION["user"]["role"] == "student"): ?>
  <h1>Student Dashboard</h1>
 <?php endif; ?>
</div>
<div style="text-align: center;">
  <p>Welcome, <?php echo $_SESSION["user"]["name"]; ?></p>
</div>
