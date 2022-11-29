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
        <h2>Exams <button onclick="window.location.href='newexam.php'" class="btn btn-primary">Create new exam</button></h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-2">Exam ID</th>
                <th class="col-md-2">See Details</th>
              </tr>
            </thead>
            <tbody id="availableexams">
            </tbody>
          </table>
          
        </div>
      </div>
      <div>
        <h2>Student Responses</h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-2">Exam ID</th>
                <th class="col-md-2">Student</th>
                <th class="col-md-2">Action</th>
              </tr>
            </thead>
            <tbody id="studentresponses">
            </tbody>
          </table>
        </div>
      </div>
      <div>
        <h2>Question Bank <button onclick="window.location.href='newquestion.php'" class="btn btn-primary">Create new question</button></h2>
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
        </div>
      </div>
    </div>
    <div class="container" style="padding: 80px 25px 75px"></div>

    <script>
      
      // localStorage.removeItem('exam'); // clear localStorage
      // fetching question bank
      let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
      fetchreq.then((res) => {
        return res.json();
      }).then((data) => {
        console.log(data);
      
        // filling table with question bank
        var questionBank = "";
        var obj = data;
        for(let i in obj) {
          questionBank += '<tr> <td class="col-md-1">' + obj[i]["id"] + '</td> <td class="col-md-3">' + obj[i]["question"] + '</td> <td class="col-md-1">' + obj[i]["difficulty"] + '</td> <td class="col-md-1">' + obj[i]["category"] + '</td> <td class="col-md-1">' + obj[i]["testcase1"] + '; ' + obj[i]["testcase2"];
          if (obj[i]["testcase3"] != null) {
            questionBank += '; ' + obj[i]["testcase3"];
          } if (obj[i]["testcase4"] != null) {
            questionBank += '; ' + obj[i]["testcase4"];
          } if (obj[i]["testcase5"] != null) {
            questionBank += '; ' + obj[i]["testcase5"];
          }
          questionBank += '</td> </tr>';
        }
        document.getElementById("bank").innerHTML = questionBank;
      });

      // filling exam table
      fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getavailableexams.php").then((res) => {
        return res.json();
      }).then((data) => {

        // send here only "exam1", "exam2", etc. (keys)
        // when taking exam1 (send "exam1"), only display exam1 data
        var exam = '';
        if (data.length > 0) {
          // if status is graded, make button lead to comments/point distribution.
          // make grade field?
          for (let i = 0; i < data.length; i++) {
            exam += '<tr> <td>' + data[i] + '</td> <td> <input class="btn btn-primary" type="submit" name="' + data[i] + '" value="See details"> </td> </tr>';
            let send = [];
            send.push(data[i]);
            console.log("send would be: " + JSON.stringify(send));
          }
          document.getElementById("availableexams").innerHTML = exam;
          console.log(data.length);
          for (let x = 0; x < data.length; x++) {
            console.log("num: " + x);
            document.getElementsByTagName("input")[x+1].addEventListener('click', function(e){
              console.log("clicked see exam!");
              // var send = [JSON.stringify(data[x])];
              let send = [];
              send.push(data[x]);
              localStorage.setItem('exam', JSON.stringify(send));
              // localStorage.setItem('exam', JSON.stringify(data));// JSON.stringify(data) worked
              window.location.href='examdetails.php';
            });
          }
        } else {
          document.getElementById("availableexams").innerHTML = '<p>No Exams</p>';
        }
        
      });


      // filling student responses table
      fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/studentresponses.php").then((res) => {
        return res.json();
      }).then((data) => {
        console.log(data);
        var ob = new Object();
        for (let i in data) {
          let exam = data[i];
          for (let i in exam) {
            let examnum = i;
            let usernames = exam[i];
            for (let i in usernames) {
              if (!ob[examnum]) {
                ob[examnum] = []
                ob[examnum].push(usernames[i]);
              }
              else {
                ob[examnum].push(usernames[i]);
              }
            }
          }
        }
        console.log(ob); // {Exam1:{student300:[]}}

        
        for (let i in ob) {
          var studentresponses = "";
          let examnum = i;
          let usernames = ob[i];
          for(let x in usernames) {
            let user = usernames[x];
            for (j in user) {
              studentresponses += '<tr> <td class="col-md-1">' + examnum + '</td> <td class="col-md-1">' + j + '</td> <td class="col-md-1"><input id="grade' + x + '" class="btn btn-primary" type="submit" name="studentresponse" value="Start auto grade"></td> </tr>';
            }
          }
          document.getElementById("studentresponses").innerHTML = studentresponses;
          for (let k in usernames) {
            let user = usernames[k];
            for (let z in user) {
              document.getElementById("grade" + k).addEventListener('click', function(e){
                console.log("grade " + i + " was clicked");
                localStorage.setItem('gradeexam', JSON.stringify(user[z]));
                window.location.href='publishscores.php';
              });
            }
          }
        }
      });
    </script>

  </body>
</html>