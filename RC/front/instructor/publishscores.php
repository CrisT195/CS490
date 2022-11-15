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
				// console.log("student un: " + studentUN);
				ob = [];
        for (let i in data2) {
          if (data2[i][2] == studentUN) {
            ob.push(data2[i]);
          }
        }
        console.log(ob); // [['4', 'unpublished', 'student300', '20', 'def (hi):\n\treturn 0', '5,-2.5,-2.5', null, null], [], ...]


				// console.log(myData);
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
						let pnts = ob[i][5].split(','); 
						examresponse += '<div> <h4>' + Number(Number(i)+1) + ') ' + obj[myData[i][2]]['question'] + '</h4>	<div class="container-fluid">	<div class="row">	<div class="col-lg-6"><p style="white-space:pre;">' + ob[i][4] + '</p>	</div>	<div class="col-lg-6">	<table class="table table-striped">	<thead>	<tr>	<th class="col-md-3">test case</th>	<th class="col-md-1">points</th>	</tr>	</thead>	<tbody >	<tr>	<td>function name</td>	<td><input type="text" name="tc2" id="' + i + 'pnt0" value="'+ pnts[0] +'"></td>	</tr>	<tr>	<td>' + obj[ob[i][3]]['testcase1'] + '</td>	<td><input type="text" name="tc2" id="' + i + 'pnt1" value="'+ pnts[1] +'"></td>	</tr> <tr> <td>' + obj[ob[i][3]]['testcase2'] + '</td> <td><input type="text" name="tc2" id="' + i + 'pnt2" value="'+ pnts[2] +'"></td> </tr> </tbody> </table> </div> </div> <label for="cmts' + i + '">Comments:</label> <textarea id="cmts' + i + '" name="w3review" rows="4" cols="50"></textarea> </div> </div><br><br>';
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
							let pntStr = document.getElementById(i + "pnt0").value + ','+ document.getElementById(i + "pnt1").value + ',' + document.getElementById(i + "pnt2").value;
							sendarr['points'] = pntStr;
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
						window.location.replace("instructorhome.php");
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
