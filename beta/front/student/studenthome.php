<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: login.php"));
 }
?>

<div style="text-align: center;">
 <h1>Student Dashboard</h1>
</div>
<div style="text-align: center;">
 <p>Welcome, <?php echo $_SESSION["user"]["name"]; ?></p>


 <script></script>
</div>
