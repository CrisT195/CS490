<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}


// $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midteacherpublish.php';
$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/butlerteacher.php';
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


// butlerteacher.php
// [
//   {"Exam1":
//     [{"student500":[
//         ["37","unpublished","student500","35","def doubleIt(a):\r\n\treturn 0","5,2,3","","5,2,3","1","1,2","72","35","50","1","35","Write a function named doubleIt","easy","variables","doubleIt(2)","doubleIt(3)",null,null,null,"4","6",null,null,null,null],
//         ["36","unpublished","student500","36","def tripleIt(a):\r\n\treturn 0","5,0,0","","5,10,10","1","1,2","73","36","25","1","36","Write a function named tripleIt","easy","variables","tripleIt(2)","tripleIt(3)",null,null,null,"6","9",null,null,null,null],
//         ["35","unpublished","student500","37","def hello(a):\r\n\treturn 0","5,0,0","","5,10,10","1","1,2","74","37","25","1","37","write hello world","easy","variables","hello(\"world\")","hello(\"cs490\")",null,null,null,"hello world","hello cs490",null,null,null,null]
//       ]}]
//   }
// ]


?>
