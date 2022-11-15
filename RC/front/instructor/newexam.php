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
    <title>Create a New Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style> #quesSelect .selected {
    background-color: brown;
    color: #FFF;
    }
    
    .split {
      height: 100%;
      width: 50%;
      position: fixed;
      z-index: 1;
      top: 0;
      overflow-x: hidden;
      padding-top: 20px;
    }

    /* Control the left side */
    .left {
      left: 0;
      /* background-color: #111; */
    }

    /* Control the right side */
    .right {
      right: 0;
      background-color: #edf2f4;
    }

    .centered {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -260px);
      text-align: center;
    }</style>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  </head>
  <body>
    <div class="split left">
    <div class="centered">
    <!-- <div class="container" style="padding: 80px 25px 75px"> -->
      <h2>Create an Exam</h2>
      <div style="display:flex">
        <input type="text" id="myInput" onkeyup="keywordSearch()" placeholder="Search by keyword..">
        <!-- <div class="form-group"> -->
        <select class="form-select" id="diffFilter" onChange="difficultyFilter()">
          <option value="" selected>filter by difficulty</option>
          <option value="easy">easy</option>
          <option value="medium">medium</option>
          <option value="hard">hard</option>
        </select>
        <!-- </div> -->
        <!-- <div class="form-group"> -->
        <select id="cateFilter" onChange="categoryFilter()">
          <option value="" selected>filter by category</option>
          <option value="variables">variables</option>
          <option value="conditionals">conditionals</option>
          <option value="for loops">for loops</option>
          <option value="while loops">while loops</option>
          <option value="lists">lists</option>
        </select>
        <!-- </div> -->
      </div> <br>

    <div class="table-responsive">
      <table id="quesSelect" class="table table-striped">
        <thead>
          <tr>
            <th class="col-md-1">id</th>
            <th class="col-md-4">question</th>
            <th class="col-md-1">difficulty</th>
            <th class="col-md-1">category</th>
            <th class="col-md-1">test cases</th>
          </tr>
        </thead>
        <tbody id="bank">
        </tbody>
      </table>
      <!-- <input type="button" name="chooseQuestions" class="chooseQuestions btn btn-primary" value="choose questions"/> -->
    </div>
    
    </div>
    </div>

    <div class="split right">
      <div class="centered">
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
    </div>

    <script>
      // fetching question bank
      let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
      fetchreq.then((res) => {
        return res.json();
      }).then((data) => {
      
      // filling table with question bank
      var questionBank = "";
      var obj = data;
      for(var i in obj) {
        questionBank += '<tr> <td class="col-md-1">' + obj[i]["id"] + '</td> <td class="col-md-1">' + obj[i]["question"] + '</td> <td class="col-md-2">' + obj[i]["difficulty"] + '</td> <td class="col-md-2">' + obj[i]["category"] + '</td> <td class="col-md-2">' + obj[i]["testcase1"] + '; ' + obj[i]["testcase2"];
        if (obj[i]["testcase3"] != null) {
          questionBank += '; ' + obj[i]["testcase3"];
        } if (obj[i]["testcase4"] != null) {
          questionBank += '; ' + obj[i]["testcase4"];
        } if (obj[i]["testcase5"] != null) {
          questionBank += '; ' + obj[i]["testcase5"];
        }
        questionBank += '</td> </tr>';
      }
      document.getElementById("bank").innerHTML = questionBank;


      // {"questions": [{"id":1, ...},{},{}]}
      // {"1":["question 2","medium","loops"],"2":["question 3","hard","arithmetic"]}

      $("#bank tr").click(function(){
        $(this).toggleClass('selected');    
        // var value=$(this).find('td').html();
        // alert(value);
        var examtable = "";
        $("#quesSelect tr.selected").each(function(){
          let id = $('td:eq(0)', this).html();
          let question = $('td:eq(1)', this).html();
          let difficulty = $('td:eq(2)', this).html();
          let category = $('td:eq(3)', this).html();
          let testcases = $('td:eq(4)', this).html();
          examtable += '<tr> <td class="col-md-1">' + id + '</td> <td class="col-md-3">' + question + '</td> <td class="col-md-1">' + difficulty + '</td> <td class="col-md-1">' + category + '</td> <td class="col-md-2">' + testcases + '</td> <td class="col-md-1"> <input size="1" id="ques' + id + '" required> </td> </tr>';//type="number"
        });
        document.getElementById("ques").innerHTML = examtable;
      });


      $('.createexam').on('click', function(e){
        // TODO: Make sure sending updated info

        var selected = {};
        var questions = [];
        $("#quesSelect tr.selected").each(function(){
          var ques = {};
          ques["id"] = $('td:eq(0)', this).html();
          ques["question"] = $('td:eq(1)', this).html();
          ques["difficulty"] = $('td:eq(2)', this).html();
          ques["category"] = $('td:eq(3)', this).html();
          ques["testcases"] = $('td:eq(4)', this).html();
          // ques["testcase2"] = $('td:eq(5)', this).html();
          questions.push(ques);
        });
        selected["questions"] = questions;



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
        // {questions: [ [questionid, pnts], [questionid, pnts], ...  ] }
        var obj1 = { "questions": qs}; 
        console.log(obj1);
        console.log(JSON.stringify(obj1));
        fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/inputnewexam.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
          },
          body: JSON.stringify(obj1)
        }).then(resp => resp.json()
        ).then((data) => {
          console.log(data);
          console.log(JSON.stringify(data));
          window.location.replace("instructorhome.php");
        });

      });

      // $('.chooseQuestions').on('click', function(e){
      //     var selected = {};
      //     var questions = [];
      //     $("#quesSelect tr.selected").each(function(){
      //       var ques = {};
      //       ques["id"] = $('td:eq(0)', this).html();
      //       ques["question"] = $('td:eq(1)', this).html();
      //       ques["difficulty"] = $('td:eq(2)', this).html();
      //       ques["category"] = $('td:eq(3)', this).html();
      //       ques["testcases"] = $('td:eq(4)', this).html();
      //       // ques["testcase2"] = $('td:eq(5)', this).html();
      //       questions.push(ques);
      //     });
      //     selected["questions"] = questions;
      //     // alert(JSON.stringify(selected));
      //     // collect question data
      //     // redirect to edit page
          
      //     localStorage.setItem( 'selectedquestions',  JSON.stringify(selected) );
      //     window.location.replace("createexam.php");

      //     // {questions: [{}, {}, {}]}

      // });
    });


    function keywordSearch() {
      // Declare variables
      let input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("quesSelect");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }

    function difficultyFilter() {
      // Declare variables
      let input, table, tr, td, i, txtValue;
      input = document.getElementById("diffFilter").value;
      console.log(input);
      table = document.getElementById("quesSelect");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2];
        console.log(td);
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.indexOf(input) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }


    function categoryFilter() {
      // Declare variables
      let input, table, tr, td, i, txtValue;
      input = document.getElementById("cateFilter").value;
      console.log(input);
      table = document.getElementById("quesSelect");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[3];
        console.log(td);
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.indexOf(input) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }

    </script>

  </body>
</html>