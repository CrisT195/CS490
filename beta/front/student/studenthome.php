<?php
 session_start();
 if(isset($_POST['logout'])) {
  $_SESSION["logout"] = "logout";
  header('Location: ../login.php');
 }
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "student")) {
  die(header("Location: ../login.php"));
 }
?>


<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
 </head>
 <body>

  <div style="text-align: center;">
   <h1>Student Dashboard</h1>
   <form method="post">
    <input  type="submit" name="logout" value="logout" class="btn btn-primary"/>
   </form>
  </div>
  <div class="container">
   <div>
    <h2>Exams</h2>
    <div class="table-responsive">
     <table class="table table-striped">
      <thead>
       <tr>
        <th class="col-md-2">Exam Name</th>
        <th class="col-md-2">take exam</th>
       </tr>
      </thead>
      <tbody id="publishedexams">
      </tbody>
     </table>
    </div>
   </div>
  </div>

  <div class="container" style="padding: 80px 25px 75px"></div>
  <script>
   // fetch published exams
   fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getavailableexams.php").then((res) => {
    return res.json();
   }).then((data) => {
    console.log(JSON.stringify(data));
    console.log(data);

    // send here only "exam1", "exam2", etc. (keys)
    // when taking exam1 (send "exam1"), only display exam1 data
    var exam;
    if (data.length > 0) {
     // if status is graded, make button lead to comments/point distribution.
     // make grade field?

     exam = '<tr> <td>' + data[0] + '</td> <td> <input class="btn btn-primary" type="submit" name="' + data[0] + '" value="Take Exam"> </td> </tr>';
    } else {
     exam = "no exams";
    }
    document.getElementById("publishedexams").innerHTML = exam;
//    <button onclick="window.location.href='newquestion.php'" class="btn btn-primary">Create new question</button>

    document.getElementsByTagName("input")[1].addEventListener('click', function(e){
     console.log("clicked take exam!")
     localStorage.setItem('exam', data[0]);
     window.location.href='takeexam.php';
    });
   });

   
  </script>
 </body>
</html>
