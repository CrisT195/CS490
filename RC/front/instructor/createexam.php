<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}
?>

<!-- will pass selected questions here, and allow instructor to add test cases and pnt values to questions before sending data to middle. -->
<!DOCTYPE html> 
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create an Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
    <div class="container" style="padding: 80px 25px 75px">
      <h2>Assign Points</h2>
      <p>Total points must add up to 100.</p>
      <!-- <div class="table-responsive"> -->
      <table id="quesSelect" class="table table-striped">
        <thead>
          <tr>
            <th class="col-md-1">id</th>
            <th class="col-md-4">question</th>
            <th class="col-md-1">difficulty</th>
            <th class="col-md-1">category</th>
            <th class="col-md-1">test cases</th>
            <th class="col-md-1">points</th>
          </tr>
        </thead>
        <tbody id="ques">
        </tbody>
      </table>
      <input type="button" name="createExam" class="createexam btn btn-primary" value="create exam"/>
    </div>

    <script>

      var myData = JSON.parse(localStorage['selectedquestions']);
      localStorage.removeItem( 'selectedquestions' ); // Clear the localStorage

      var questions = myData["questions"];
      var examtable = "";
      for(var i in questions) {
        examtable += '<tr> <td class="col-md-1">' + questions[i]["id"] + '</td> <td class="col-md-1">' + questions[i]["question"] + '</td> <td class="col-md-2">' + questions[i]["difficulty"] + '</td> <td class="col-md-2">' + questions[i]["category"] + '</td> <td class="col-md-2">' + questions[i]["testcases"] + '</td> <td class="col-md-2"> <input type="number" id="ques' + questions[i]["id"] + '" required> </td> </tr>';
      }
      document.getElementById("ques").innerHTML = examtable;
      $('.createexam').on('click', function(e){
        // TODO: check that points add to 100
        var sum = 0;
        var qs = [];
        for(var x in questions) {
          var pnts = document.getElementById("ques" + questions[x]["id"]).value;
          // console.log(pnts);
          if (pnts == "") {
            alert("Enter point values for all questions");
            return true;
          }
          // console.log(document.getElementById("ques" + questions[x]["id"]).value);
          sum += Number(pnts);
          qs[x] = [Number(questions[x]["id"]), pnts];
        }
        console.log(sum);
        if (sum != 100) {
          alert("Question points must add up to 100");
          return true;
        }
        // {exam: [ [questionid, pnts], [questionid, pnts], ...  ] }
        var obj = { "questions": qs}; // changed from "exam" to "questions"?
        fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/inputnewexam.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
          },
          body: JSON.stringify(obj)
        }).then(resp => resp.json()
        ).then((data) => {
          console.log(data);
          console.log(JSON.stringify(data));
          window.location.replace("instructorhome.php");
        });

      });


    </script>
  </body>
</html>
