<?php
session_start();
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
    <title>Exam in-progress</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
    <div class="container">
      <h3>Exam in progress</h3>
      <div id="examquestions"></div>
      <input id="submitexam" type="submit" name="submit" value="Submit Exam" class="btn btn-primary"/>
    </div>

    <script>
      var ex = [localStorage['exam']];
      localStorage.removeItem('exam'); // clear localStorage
      console.log("exam name: " + ex);
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
        // [["published","student300","16","def hi(a):\\n\\treturn 0","5,5,5","Good",null]]
        // [[status, username, questionid, question, pnts, comments], ..]
        console.log(data); // [{question:'', pnts: '15', questionId: '20', etc.}, {…}, {…}]

        var dataLen = data.length;
        // exam
        if (dataLen < 1) {
          window.location.href='studenthome.php';
          return true;
        }
        var exam = "";
        
        for (let i in data) {
          var ques = data[i]["question"];
          exam += '<br><div><h3>'+i+". " + ques + '&emsp;<b> (' + data[i]["pnts"] + ' points)</b></h3><textarea id="textarea'+ i +'"></textarea>' + '</div><br>';
        }
        document.getElementById("examquestions").innerHTML = exam;

        $("textarea").keydown(function(e) {
          if(e.keyCode === 9) { // tab was pressed
            // get caret position/selection
            var start = this.selectionStart;
            var end = this.selectionEnd;

            var $this = $(this);
            var value = $this.val();

            // set textarea value to: text before caret + tab + text after caret
            $this.val(value.substring(0, start) + "\t" + value.substring(end));

            // put caret at right position again (add one for the tab)
            this.selectionStart = this.selectionEnd = start + 1;

            // prevent the focus lose
            e.preventDefault();
          }
        });

        document.getElementById("submitexam").addEventListener('click', function(e){
          console.log("clicked submit exam");
          for (let i in data) {
            var temp = document.getElementById("textarea" + i).value;
            var quesObj = {
              "examqid": data[i]["questionId"],
              "answer": temp//JSON.stringify(temp)
            };
            console.log(JSON.stringify(temp));
            console.log("textarea number: " + i);
            console.log(quesObj);
            fetch("https://afsaccess4.njit.edu/~cth9/CS490/student/sendquesresponse.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
              },
              body: JSON.stringify(quesObj)
            }).then(resp => resp.json()
            ).then((data) => {
              console.log(data);
            });
          }
          window.location.replace("studenthome.php");
        });

      });
    </script>
  </body>
</html>
