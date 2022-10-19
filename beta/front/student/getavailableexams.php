<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "student")) {
  die(header("Location: ../login.php"));
 }

 // [{"exam1":
 //    [[{
 //     "id":"20",
 //     "questionId":"20",
 //     "pnts":"15",
 //     "examnum":"1",
 //     "question":"Define hi",
 //     "difficulty":"Easy",
 //     "category":"Loops",
 //     "testcase1":"hi(1) == 0",
 //     "testcase2":"hi(0) == 1",
 //     "output1":"1",
 //     "output2":"0"},
 //     {...},{...}
 //    ]]
 // }]

 $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midallexams.php';

 $ch = curl_init($url); 
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 curl_close($ch);

 $decoded_json = json_decode($result, true);

 // gives exam name keys. ex: "exam1"
 echo json_encode(array_keys($decoded_json[0]));

?>
