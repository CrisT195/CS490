<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] == "student" || $_SESSION["user"]["role"] == "teacher"))) {
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

 $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midallexams.php'; // real link
 // $url = 'https://afsaccess4.njit.edu/~bjb38/CS490/midteacherpublish.php';// for getting exams for teacher dashboard

 $ch = curl_init($url); 
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 curl_close($ch);

 $decoded_json = json_decode($result, true);

 // echo $result;
 // gives exam name keys. ex: "exam1"
 $imdone = [];
 foreach ($decoded_json as $val) {
  array_push($imdone, array_keys($val)[0]);
 }
 // echo json_encode($decoded_json);
 // echo json_encode(array_keys($decoded_json[0])); // 
 echo json_encode($imdone);
 //placeholder data
 // $json_placeholder = '["exam1", "exam2"]';
 //  echo $json_placeholder;

?>