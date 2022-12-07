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
    <title>Exam in-progress</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
    <div class="container" style="padding: 80px 25px 75px">
      <h3>Exam Details</h3>
			<div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-1">id</th>
                <th class="col-md-3">question</th>
                <th class="col-md-1">difficulty</th>
                <th class="col-md-1">category</th>
                <th class="col-md-1">test cases</th>
                <th class="col-md-1">points</th>
              </tr>
            </thead>
            <tbody id="examquestions">
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
			var ex = JSON.parse(localStorage['exam']);
      localStorage.removeItem('exam'); // clear localStorage
			console.log(JSON.stringify(ex));
      fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getexamquestions.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(ex)
      }).then((res) => {
        return res.json();
      }).then((data) => {
        console.log(data);

        var dataLen = data.length;
        // exam
        if (dataLen < 1) {
          window.location.href='instructorhome.php';
          return true;
        }
        var examquestions = "";
        
        for (var i in data) {
          examquestions += '<tr> <td>' + data[i]["id"] + '</td> <td>' + data[i]["question"] + '</td> <td>' + data[i]["difficulty"] + '</td> <td>' + data[i]["category"] + '</td> <td>' + data[i]["testcase1"] + ',' + data[i]["testcase2"];
          if (data[i]["testcase3"] != null) {
            examquestions += '; ' + data[i]["testcase3"];
          } if (data[i]["testcase4"] != null) {
            examquestions += '; ' + data[i]["testcase4"];
          } if (data[i]["testcase5"] != null) {
            examquestions += '; ' + data[i]["testcase5"];
          }
          examquestions += '</td> <td>' + data[i]["pnts"] + '</td> </tr>';
        }
        document.getElementById("examquestions").innerHTML = examquestions;


      });
    </script>
  </body>
</html>