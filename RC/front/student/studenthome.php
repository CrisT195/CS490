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
								<th class="col-md-2">take exam</th>
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
						console.log("clicked take exam!")
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
			console.log(data); // [["4","published","student300","20","def (hi):\n\treturn 0","5,-2.5,0","test comments",null],[...]]
			
			var gradedexams = '';
			if (data.length > 0) {
				gradedexams += '<tr> <td>Score Published</td> <td> <input class="btn btn-primary" type="submit" id="seescore" value="See Score"> </td> </tr>';
				document.getElementById("gradedexams").innerHTML = gradedexams;
				document.getElementById("seescore").addEventListener('click', function(e) {
					console.log("clicked see score!");
					window.location.href='viewscore.php';
				});
			}
		});	
		
		</script>
	</body>
</html>