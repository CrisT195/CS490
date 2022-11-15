<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}
?>

<?php
// Asks middle for question bank, and returns to front

$url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midallquestions.php';
$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
echo $result;


// ex: { "questions" : [ {"id":0, "description":"Given a string name, e.g. 'Bob', create a function named 'hello_name' that returns a greeting of the form 'Hello Bob!'." , "difficulty":"easy", "category":"strings" }, {"id":1, "description":"question 2" , "difficulty":"medium", "category":"loops" }, {"id":2, "description":"question 3" , "difficulty":"hard", "category":"arithmetic" } ] }

// updated:
// [{"id":"35","question":"Write a function named doubleIt","difficulty":"easy","category":"variables","testcase1":"doubleIt(2)","testcase2":"doubleIt(3)","testcase3":null,"testcase4":null,"testcase5":null,"output1":"4","output2":"6","output3":null,"output4":null,"output5":null,"constraint":null},...]
?>
