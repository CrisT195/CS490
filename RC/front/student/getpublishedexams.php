<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] == "student" || $_SESSION["user"]["role"] == "teacher"))) {
  die(header("Location: ../login.php"));
 }

 // gets graded exams. must send student's username.

 // $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midstudentpublish.php';
 $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/butlerstudent.php';

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
 // echo array_keys(json_decode($result, true));

// [["published","student300","16","def hi(a):\\n\\treturn 0","5,5,5","Good",null]]

// butlerstudent.php
// [
//  {"Exam2":
//   [{"student500":
//    [["38","published","student500","37","def hello(a):\r\n\treturn 0","5,0,0","","5","2","1,2","77","37","20","2","37","write hello world","easy","variables","hello(\"world\")","hello(\"cs490\")",null,null,null,"hello world","hello cs490",null,null,null,null]]
//   }]
//  }
// ]

?>