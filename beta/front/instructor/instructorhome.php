<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  </head>
  <body>
    <div style="text-align: center;">
      <h1>Instructor Dashboard</h1>
    </div>
    <div class="container">
      <div>
        <h2>Question Bank</h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-1">id</th>
                <th class="col-md-4">question</th>
                <th class="col-md-1">difficulty</th>
                <th class="col-md-1">category</th>
              </tr>
            </thead>
            <tbody id="bank">
            </tbody>
          </table>
          <button onclick="window.location.href='newquestion.php'">Create new question</button>
        </div>
      </div>
      <div>
        <h2>Exams</h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-2">Exam ID</th>
                <th class="col-md-2">status</th>
              </tr>
            </thead>
            <tbody id="exams">
            </tbody>
          </table>
          <button onclick="window.location.href='newexam.php'">Create new exam</button>
        </div>
      </div>
    </div>

    <script>
      // fetching question bank
      let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
      // fetchreq.then(res => res.json().then(d => {console.log(d)}));
      fetchreq.then((res) => {
        return res.json();
      }).then((data) => {
      
      const q = data;
      // filling table with question bank
      var questionBank = "";
      var obj = q["questions"];
      for(var i in obj) {
        questionBank += '<tr> <td class="col-md-1">' + obj[i]["id"] + '</td> <td class="col-md-1">' + obj[i]["description"] + '</td> <td class="col-md-2">' + obj[i]["difficulty"] + '</td> <td class="col-md-3">' + obj[i]["category"] + '</td> </tr>';
      }
      document.getElementById("bank").innerHTML = questionBank;
    });

      // filling exam table
      document.getElementById("exams").innerHTML = '<p>No Exams</p>';
    </script>



  </body>
</html>
