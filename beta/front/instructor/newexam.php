<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}
?>

<!-- Will allow instructor to select questions from questionbank to be added to the exam. 
Afterwards, directs to 'createexam.php' to add pnts and test cases. -->

<!DOCTYPE html> 
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style> #quesSelect .selected {
    background-color: brown;
    color: #FFF;
}</style>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
    <div class="container" style="padding: 80px 25px 75px">
    <h2>Create an Exam</h2>
    
    <div class="table-responsive">
          <table id="quesSelect" class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-1">id</th>
                <th class="col-md-4">question</th>
                <th class="col-md-1">difficulty</th>
                <th class="col-md-1">category</th>
              </tr>
            </thead>
            <tbody id="bank">
            </tbody>
          </table>
          <input type="button" name="chooseQuestions" class="chooseQuestions btn btn-primary" value="choose questions"/>
        </div>
    
    </div>
    <script>
      // fetching question bank
      let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
      fetchreq.then((res) => {
        return res.json();
      }).then((data) => {
      
      const q = data;
      // filling table with question bank
      var questionBank = "";
      var obj = q["questions"];
      for(var i in obj) {
        questionBank += '<tr> <td class="col-md-1">' + obj[i]["id"] + '</td> <td class="col-md-1">' + obj[i]["description"] + '</td> <td class="col-md-2">' + obj[i]["difficulty"] + '</td> <td class="col-md-2">' + obj[i]["category"] + '</td> </tr>';
      }
      document.getElementById("bank").innerHTML = questionBank;


      // {"questions": [{"id":1, ...},{},{}]}
      // {"1":["question 2","medium","loops"],"2":["question 3","hard","arithmetic"]}

      $("#quesSelect tr").click(function(){
        $(this).toggleClass('selected');    
        var value=$(this).find('td').html();
        // alert(value);    
      });

      $('.chooseQuestions').on('click', function(e){
          var selected = {};
          var questions = [];
          $("#quesSelect tr.selected").each(function(){
            var ques = {};
            ques["id"] = $('td:eq(0)', this).html();
            ques["description"] = $('td:eq(1)', this).html();
            ques["difficulty"] = $('td:eq(2)', this).html();
            ques["category"] = $('td:eq(3)', this).html();
            questions.push(ques);
          });
          selected["questions"] = questions;
          alert(JSON.stringify(selected));
          // collect question data
          // redirect to edit page

          let options = {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(selected)
          }

          // let fetchRes = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/createexam.php", options);
          // fetchRes.then(res =>
          //   res.json()).then(d => {
          //       console.log(d)
          // });

      });
    });
    </script>

  </body>
</html>
