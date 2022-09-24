<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">


<div class="container" style="padding: 50px 25px 75px">
 <form method="POST">
  <div class="form-group">
   <label for="username">Username:</label>
   <input class="form-control" type="username" id="un" name="username" required/>
  </div>
  <div class="form-group">
   <label for="pw">Password:</label>
   <input class="form-control" type="password" id="pw" name="password" required/>
  </div>
  <input class="btn btn-primary" type="submit" name="login" value="Login"/>
 </form>
 
</div>

<?php
if (isset($_POST["username"]) && isset($_POST["password"])){
 $username = $_POST["username"];
 $password = hash("md5", $_POST["password"]);
 
 
 $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midlogin.php';
 $data = array("username" => $username, "password" => $password);

 $data_string = http_build_query($data); 
 $ch = curl_init($url); 
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 echo $result;
 echo "<br />";
 $decoded_json = json_decode($result, true);
 var_dump($data);
 if ($decoded_json['role'] == "student") {
  // generate portal dashboard
 } else if ($decoded_json['role'] == "teacher") {
  // generate portal dashboard
 } else {
  // push warning
 }
}
?>
