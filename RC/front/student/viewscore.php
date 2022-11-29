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
				var ob = [];
				for (let i in data1) {
          			let exam = data1[i];
          			for (let i in exam) {
            			let usernames = exam[i];
            			for (let i in usernames) {
							responses = usernames[i];
							for (let i in responses) {
								individualResponses = responses[i];
								for (let i in individualResponses) {
									ob.push(individualResponses[i]);
								}
							}
						}
					}
				}
				var myData = ob.slice();
				for (let i in myData) {
					myData[i] = myData[i].slice(1);
				}
				console.log(myData);
				console.log(ob); // [["4","published","student300","20","def (hi):\n\treturn 0","5,-2.5,0","test comments",null],[...]]  ob
				var quesids = [];
				for(let i in ob) {
					quesids.push(ob[i][3]);
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
				}).then((obj) => {
					console.log(obj); // {16:{question:'', }} obj

					var examresponse = "";
					for (let i in ob) {
						let earnedpnts = ob[i][5].split(','); 
						let finalpnts = ob[i][7].split(',');					
						
						let funcname = obj[myData[i][2]]['testcase1'];
						let expecFuncName = funcname.substring(0, funcname.indexOf('('));
						let funcname2 = ob[i][4];
						let actualFuncName = funcname2.substring(4, funcname2.indexOf("("));
						
						let testCases = ob[i].slice(18,23);
						let testCaseOutputs = ob[i].slice(23,28);
						let userOutputs = ob[i][9].split(',');
						testCases = testCases.filter(element => {
  							return element !== null;
						});
						testCaseOutputs = testCaseOutputs.filter(element => {
  							return element !== null;
						});

						examresponse += '<div> <h4>' + Number(Number(i)+1) + ') ' + obj[myData[i][2]]['question'] + '</h4>	<div class="container-fluid">	<div class="row">	<div class="col-lg-6"><p style="white-space:pre;">' + ob[i][4] + '</p>	</div>	<div class="col-lg-6">	<table class="table table-striped">	<thead>	<tr>	<th class="col-md-1">Cases</th>	<th class="col-md-1">Expected Result</th> <th class="col-md-1">Actual Result</th> <th class="col-md-1">Points Worth</th>	<th class="col-md-1">Points Earned</th></tr> </thead> <tbody> <tr> <td>Function Name</td> <td>' + expecFuncName + '</td> <td>' + actualFuncName + '</td> <td>' + finalpnts[0] +  '</td> <td>' + earnedpnts[0] + '</td></tr><tr>';

						earnedpnts.shift();
						finalpnts.shift();
						for (let x in testCases) {
							examresponse += '<td>' + testCases[x] + '</td> <td>' + testCaseOutputs[x] + '</td> <td>' + userOutputs[x] + '</td><td>' + finalpnts[x] + '</td> <td>' + earnedpnts[x] + '</td></tr><tr>';
						}

						if (obj[myData[i][2]]['constraint'] != null) {
							if (earnedpnts[earnedpnts.length - 1] == 3) {
								examresponse += '<td>Constraint</td> <td>' + obj[myData[i][2]]['constraint'] + '</td> <td>' + obj[myData[i][2]]['constraint'] + '</td><td>' + finalpnts[finalpnts.length-1] + '</td><td>' + earnedpnts[earnedpnts.length-1] + '</td> </tr><tr>';
							}
							else if (earnedpnts[earnedpnts.length - 1] == 0) {
								examresponse += '<td>Constraint</td> <td>' + obj[myData[i][2]]['constraint'] + '</td> <td>none</td> <td>' + finalpnts[finalpnts.length-1] + '</td><td>' + earnedpnts[earnedpnts.length-1] + '</td> </tr><tr>';
							}
						}
						else {
							examresponse += '<td>Constraint</td> <td>No Constraint</td> <td>No Constraint</td> <td>0</td> <td>0</td> </tr><tr>';
						}

						let comments = ob[i][6];
						examresponse += '<td>Comments</td> <td>' + comments + '</td> </tr></tbody></table></div>';
						
					}
					document.getElementById("examresponse").innerHTML = examresponse;
					let sum = 0;
					for (let i in ob) {
						let earnedpnts = ob[i][5].split(','); 
						if (earnedpnts[earnedpnts.length - 1] == "N") {
							earnedpnts.pop()
							for (let x = 0; x < earnedpnts.length; x++) {
								sum += Number(earnedpnts[x]);
								console.log(sum);
							}
						}
					}
					document.getElementById("score").innerHTML = sum;
				});
			});
		</script>
  </body>
</html>