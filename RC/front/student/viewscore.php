<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] == "student" || $_SESSION["user"]["role"] == "teacher"))) {
  die(header("Location: ../login.php"));
}
?>

<!DOCTYPE html> 
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Exam Score</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
		<div class="container" style="padding: 20px 50px">
			<div>
				<div >
					<h2>View Exam Score</h2>
				</div> <br><br>
				<h3>Total Grade: <span id="score"></span></h3>
        <div id="examresponse" class="container" style="padding: 30px 100px 75px 100px">
					
					
        </div>
      </div>
		</div>
		<div class="container" style="padding: 80px 25px 75px"></div>

		<script>
			fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getpublishedexams.php").then((res) => {
				return res.json();
			}).then((data1) => {
				console.log(data1); // [["4","published","student300","20","def (hi):\n\treturn 0","5,-2.5,0","test comments",null],[...]]  ob
				var quesids = [];
				for(let i in data1) {
					quesids.push(data1[i][3]);
				}
				console.log(quesids);

				fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getquesfromid.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
						"Accept": "application/json"
					},
					body: JSON.stringify(quesids)
				}).then((res) => {
					return res.json();
				}).then((data2) => {
					console.log(data2); // {16:{question:'', }} obj

					var examresponse = "";
					var pntsum = 0;
					for (let i in data1) {
						let pnts = data1[i][5].split(','); 
						console.log(data2[data1[i][3]]['question']);
						console.log(data2[data1[i][3]]['category']);
						console.log(data2[data1[i][3]]['difficulty']);
						console.log(data2[data1[i][3]]['testcase1']);
						console.log(data2[data1[i][3]]['testcase2']);
						console.log(data1[i][4]); // student response
						console.log(pnts); // autograde
						pntsum += Number(pnts[0]) + Number(pnts[1]) + Number(pnts[2]);
						examresponse += '<div> <h4>' + Number(Number(i)+1) + ') ' + data2[data1[i][3]]['question'] + '</h4>	<div class="container-fluid">	<div class="row">	<div class="col-lg-6"><p style="white-space:pre;">' + data1[i][4] + '</p>	</div>	<div class="col-lg-6">	<table class="table table-striped">	<thead>	<tr>	<th class="col-md-3">test case</th>	<th class="col-md-1">points</th>	</tr>	</thead>	<tbody >	<tr>	<td>function name</td>	<td>'+ pnts[0] +'</td>	</tr>	<tr>	<td>' + data2[data1[i][3]]['testcase1'] + '</td>	<td>'+ pnts[1] +'</td>	</tr> <tr> <td>' + data2[data1[i][3]]['testcase2'] + '</td> <td>'+ pnts[2] +'</td> </tr> </tbody> </table> </div> </div> <label for="cmts' + i + '">Comments: </label>' + data1[i][6] + '</div> </div><br><br>';
					}
					document.getElementById("examresponse").innerHTML = examresponse;
					document.getElementById("score").innerHTML = pntsum;
				});
			});
		</script>
  </body>
</html>