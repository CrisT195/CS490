<?php
session_start();
if(isset($_POST['logout'])) {
  $_SESSION["logout"] = "logout";
  header('Location: ../login.php');
}
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
      <form method="post">
        <input  type="submit" name="logout" value="logout" class="btn btn-primary"/>
      </form>
    </div>
    <div class="container">
      <div>
        <h2>Question Bank</h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-1">id</th>
                <th class="col-md-3">question</th>
                <th class="col-md-1">difficulty</th>
                <th class="col-md-1">category</th>
                <th class="col-md-1">test cases</th>
              </tr>
            </thead>
            <tbody id="bank">
            </tbody>
          </table>
          <button onclick="window.location.href='newquestion.php'" class="btn btn-primary">Create new question</button>
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
          <button onclick="window.location.href='newexam.php'" class="btn btn-primary">Create new exam</button>
        </div>
      </div>
    </div>
    <div class="container" style="padding: 80px 25px 75px"></div>

    <script>
      // fetching question bank
      let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
      // fetchreq.then(res => res.json().then(d => {console.log(d)}));
      fetchreq.then((res) => {
        return res.json();
      }).then((data) => {
        console.log(data);
      
        // const q = data;
        // filling table with question bank
        var questionBank = "";
        var obj = data;
        for(var i in obj) {
          questionBank += '<tr> <td class="col-md-1">' + obj[i]["id"] + '</td> <td class="col-md-3">' + obj[i]["question"] + '</td> <td class="col-md-1">' + obj[i]["difficulty"] + '</td> <td class="col-md-1">' + obj[i]["category"] + '</td> <td class="col-md-1">' + obj[i]["testcase1"] + '; ' + obj[i]["testcase2"] + '</td> </tr>';
        }
        document.getElementById("bank").innerHTML = questionBank;
      });

      // filling exam table
      // fetch("https://afsaccess4.njit.edu/~bjb38/CS490/midallexams.php")
      document.getElementById("exams").innerHTML = '<p>No Exams</p>';
    </script>

  </body>
</html>
