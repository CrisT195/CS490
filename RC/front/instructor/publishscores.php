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
    <title>Publish Exam Score</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
		<div class="container" style="padding: 20px 50px">
			<div>
				<div >
					<h2>Publish Exam Score</h2>
				</div> <br><br>
				<h3>Total Grade: <span id="score"></span></h3>
        <div id="examresponse" class="container" style="padding: 30px 100px 75px 100px">
					
					
        </div>
				<button id="submitscore" class="btn btn-primary">Submit Score</button>
      </div>
		</div>
		<div class="container" style="padding: 80px 25px 75px"></div>

		<script>
			var myData = JSON.parse(localStorage['gradeexam']);
      localStorage.removeItem( 'gradeexam' );
			console.log(myData);
			for (let i in myData) {
				myData[i] = myData[i].slice(1);
			}
			var studentUN = myData[0][1];
			var reqGrade = {'response':JSON.stringify(myData)};//
			console.log("sending to sendforgrade: ");
			console.log(reqGrade);
			console.log(JSON.stringify(reqGrade));
			fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/getautograde.php", {
				method: "POST", 
				headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
          },
        body: JSON.stringify(reqGrade)
			}).then((res) => {
				return res.json();
			}).then((data1) => {
				console.log(data1);
			});


			fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/studentresponses.php").then((res) => {
        	return res.json();
      	}).then((data2) => {
        console.log(data2);
        var ob = [];
		for (let i in data2) {
          let exam = data2[i];
          for (let i in exam) {
            let usernames = exam[i];
            for (let i in usernames) {
				responses = usernames[i];
				for (let i in responses) {
					if (i == studentUN) {
						individualResponses = responses[i];
						for (let i in individualResponses) {
							ob.push(individualResponses[i]);
						}
					}
				}
			  }
			}
		}
        console.log(ob); // [['4', 'unpublished', 'student300', '20', 'def (hi):\n\treturn 0', '5,-2.5,-2.5', null, null], [], ...]


				console.log(myData);
				var quesKeys = [];
				for (let i in myData) {
					quesKeys.push(myData[i][2]);
				}
				// console.log(quesKeys);

				var obj = new Object();
				let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
				fetchreq.then((res) => {
					return res.json();
				}).then((data) => {
					console.log(data);
					for (let i in data) {
						if (quesKeys.includes(data[i]['id'])) {
							obj[data[i]['id']] = data[i];
						}
					}
					console.log(obj); // 16: {id: '16', question: 'Define hi', difficulty: 'Easy', category: 'Loops', testcase1: 'hi(1) == 0'
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

						examresponse += '<div> <h4>' + Number(Number(i)+1) + ') ' + obj[myData[i][2]]['question'] + '</h4>	<div class="container-fluid">	<div class="row">	<div class="col-lg-6"><p style="white-space:pre;">' + ob[i][4] + '</p>	</div>	<div class="col-lg-6">	<table class="table table-striped">	<thead>	<tr>	<th class="col-md-1">Cases</th>	<th class="col-md-1">Expected Result</th> <th class="col-md-1">Actual Result</th> <th class="col-md-1">Points Worth</th>	<th class="col-md-1">Points Earned</th></tr> </thead> <tbody> <tr> <td>Function Name</td> <td>' + expecFuncName + '</td> <td>' + actualFuncName + '</td> <td>' + finalpnts[0] +  '</td> <td><input type="text" name="tc2" id="' + i + 'pnt0" value="' + earnedpnts[0] +'"></td></tr><tr>';

						earnedpnts.shift();
						finalpnts.shift();
						let j = 1;
						for (let x in testCases) {
							examresponse += '<td>' + testCases[x] + '</td> <td>' + testCaseOutputs[x] + '</td> <td>' + userOutputs[x] + '</td><td>' + finalpnts[x] + '</td> <td><input type="text" name="tc2" id="' + i + 'pnt' + j + '" value="'+ earnedpnts[x] +'"></td></tr><tr>';
							j++;
						}

						if (obj[myData[i][2]]['constraint'] != null) {
							if (earnedpnts[earnedpnts.length - 1] == 3) {
								examresponse += '<td>Constraint</td> <td>' + obj[myData[i][2]]['constraint'] + '</td> <td>' + obj[myData[i][2]]['constraint'] + '</td><td>' + finalpnts[finalpnts.length-1] + '</td><td>' + earnedpnts[earnedpnts.length-1] + '</td> </tr></tbody></table>';
							}
							else if (earnedpnts[earnedpnts.length - 1] == 0) {
								examresponse += '<td>Constraint</td> <td>' + obj[myData[i][2]]['constraint'] + '</td> <td>none</td> <td>' + finalpnts[finalpnts.length-1] + '</td><td>' + earnedpnts[earnedpnts.length-1] + '</td> </tr></tbody></table>';
							}
						}
						else {
							examresponse += '</tr></tbody></table>';
						}

						examresponse += '</div> </div> <label for="cmts' + i + '">Comments:</label> <textarea id="cmts' + i + '" name="w3review" rows="4" cols="50"></textarea> </div> </div><br><br>';
						
					}
					document.getElementById("examresponse").innerHTML = examresponse;
					let pntsum = 0;
					document.querySelectorAll('input').forEach((input) => {pntsum += Number(input.value);});
					document.getElementById("score").innerHTML = pntsum;

					let inputs = document.getElementsByTagName('input');
					for (let i = 0; i < inputs.length; i++) {
						document.getElementsByTagName('input')[i].addEventListener("change",function () {
							console.log("input changed!");
							let pntsum = 0;
							document.querySelectorAll('input').forEach((input) => {pntsum += Number(input.value);});
							document.getElementById("score").innerHTML = pntsum;
							console.log(pntsum);
						});
					}


					document.getElementById("submitscore").addEventListener("click", function() {
						console.log("clicked submit!");
						console.log(ob); // [['4', 'unpublished', 'student300', '20', 'def (hi):\n\treturn 0', '5,-2.5,-2.5', null, null], [], ...]
						for (let i in ob) {
							let sendarr = new Object();
							pntsStr = "";
							let finalpnts = ob[i][7].split(',');
							if (finalpnts[finalpnts.length -1] == "N") {
								finalpnts.pop();
								for (let x = 0; x < finalpnts.length; x++) {
									pntsStr += document.getElementById(i + "pnt" + x).value + ","
								}
							}
							sendarr['points'] = pntsStr;
							sendarr['studentId'] = ob[i][2];
							sendarr['examqid'] = ob[i][3];
							sendarr['comments'] = document.getElementById("cmts" + i).value;
							console.log(sendarr);

							fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/inputscore.php", {
								method: "POST",
								headers: {
									"Content-Type": "application/json",
									"Accept": "application/json"
								},
								body: JSON.stringify(sendarr)
							}).then(resp => resp.json()
							).then((data) => {
								console.log(data);
							});
						}
						// {points:"", studentId: "", examqid: "", comments: ""}
						//window.location.replace("instructorhome.php");
					});

				});
				
      });

			///////////////////////////////////////////////////////

			
			
			
		</script>
  </body>
</html>

<!-- myData
[
	["4","unpublished","student300","20","def (hi):\n\treturn 0",null,null,null],
	["5","unpublished","student300","16","def (hi):\n\treturn 0",null,null,null],
	["6","unpublished","student300","21","def (hi):\n\treturn 0",null,null,null]
]
obj (questions):
16: {id:'16', question:'Define hi', ...}
-->