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
								<th class="col-md-2">Exam</th>
								<th class="col-md-2">Action</th>
							</tr>
						</thead>
						<tbody id="availableexams">
						</tbody>
						<tbody id="gradedexams">
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="container" style="padding: 80px 25px 75px"></div>
		<script>
		// fetch available exams
		fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getavailableexams.php").then((res) => {
			return res.json();
		}).then((data) => {
			console.log(JSON.stringify(data));
			console.log(data);

			// send here only "exam1", "exam2", etc. (keys)
			// when taking exam1 (send "exam1"), only display exam1 data
			var exam = '';
			if (data.length > 0) {
				for (let i = 0; i < data.length; i++) {
					exam += '<tr> <td>' + data[i] + '</td> <td> <input class="btn btn-primary" type="submit" name="' + data[i] + '" value="Take Exam"> </td> </tr>';
				}
				document.getElementById("availableexams").innerHTML = exam;
				for (let x = 0; x < data.length; x++) {
					document.getElementsByTagName("input")[x+1].addEventListener('click', function(e){
						console.log("clicked take exam!");
						localStorage.setItem('exam', data[x]);
						window.location.href='takeexam.php';
					});
				}
			} else {
				exam = "no exams";
				document.getElementById("availableexams").innerHTML = exam;
			}
			
		});

		// add published (graded) exams
		fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getpublishedexams.php").then((res) => {
			return res.json();
		}).then((data) => {
			console.log(JSON.stringify(data));
			console.log(data); // [{"Exam1":[{"student300":[{"id":"41","status":"published","studentId":"student300","examQuestionId":"41","answer":"...","awardedpnts":null,"comments":null,"finalpnts":null,"exam":"1","answeroutput":null,"questionId":"41","pnts":"25","examnum":"1","question":"...","difficulty":"medium","category":"forloops","testcase1":"largest()","testcase2":"largest()","testcase3":null,"testcase4":null,"testcase5":null,"output1":"9","output2":"8","output3":null,"output4":null,"output5":null,"extras":"for"},{}] }] }]

			let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/getusername.php");
			var studentun = "";
			fetchreq.then((res) => {
				return res.json();
			}).then((username) => {
				studentun = username[0];
				console.log(studentun + ", current user");

				var exams = data[0];
				var gradedexams = '';
				for (let key in exams) {
					console.log(exams[key]); // [{"student300": [{},{}] }]
					console.log(exams[key][0]); // {"student300":[{},{}] }
					let studentlist = exams[key][0];
					for (let student in studentlist) {
						console.log(student + ", from data list");

						if (student == studentun) {
							console.log(student + " is a match!!");

							gradedexams += '<tr> <td>' + key + '</td> <td> <input class="btn btn-primary" type="submit" id="graded' + key + '" value="See Score"> </td> </tr>';
							document.getElementById("gradedexams").innerHTML += gradedexams;
							document.getElementById("graded" + key).addEventListener('click', function(e) {
								console.log("clicked see score!");
								console.log(studentlist[student]);
								localStorage.setItem('gradedexam', JSON.stringify(studentlist[student]));
								window.location.href='viewscore.php';
							});

							break;
						}
						
					}
					
				}
			});
			
		});	
		
		</script>
	</body>
</html>