<?php
session_start();
if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
}

?>

<!-- Page to add new question to the question bank.
TODO: send new question to middle end -->

<!DOCTYPE html>
<html>

<head>
  <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
  </script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>/* Split the screen in half */
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
</head>

<body>

<div class="split left">
<div class="centered">
  <h2>Add a question to the Question Bank</h2>
  <form method="POST">
    <div class="form-group">
      <label for="quesInput">Question:</label>
      <textarea class="form-control"  id="quesInput" name="quesInput" style="width: 400px" required></textarea>
    </div>
  <div class="form-group">
    <label for="quesDifficulty">Difficulty:</label>
    <!-- <input class="form-control" id="quesDifficulty" name="difficulty" required/> -->
    <select id="quesDifficulty" required>
      <option value="" disabled selected>Select difficulty</option>
      <option value="easy">Easy</option>
      <option value="medium">Medium</option>
      <option value="hard">Hard</option>
    </select>
  </div>
  <div class="form-group">
    <label for="quesCategory">Category:</label>
    <!-- <input class="form-control" id="quesCategory" name="category" required/> -->
    <select id="quesCategory" required>
      <option value="" disabled selected>Select category</option>
      <option value="variables">Variables</option>
      <option value="conditionals">Conditionals</option>
      <option value="forloops">For Loops</option>
      <option value="whileloops">While Loops</option>
      <option value="lists">Lists</option>
    </select>
  </div>
  <div class="form-group">
    <label for="constraint">Constraint:</label>
    <select id="constraint">
      <option value="" selected>None</option>
      <option value="for">for loop</option>
      <option value="while">while loop</option>
      <option value="recursion">recursion</option>
    </select>
  </div>
  

  <table id="testcaseTable" class="table table-striped">
    <thead>
      <tr>
        <th class="col-md-1">#</th>
        <th class="col-md-3">Testcase</th>
        <th class="col-md-1">Expected Output</th>
      </tr>
    </thead>
    <tbody id="testcases">
      <tr id="tc1">
        <td class="col-md-1">1</td>
        <td><input id="testcase1" type="text" required></td>
        <td><input id="output1" type="text" required></td>
      </tr>
      <tr id="tc2">
        <td class="col-md-1">2</td>
        <td><input id="testcase2" type="text" required></td>
        <td><input id="output2" type="text" required></td>
      </tr>
      <tr id="tc3" style="display: none">
        <td class="col-md-1">3</td>
        <td><input id="testcase3" type="text"></td>
        <td><input id="output3" type="text"></td>
      </tr>
      <tr id="tc4" style="display: none">
        <td class="col-md-1">4</td>
        <td><input id="testcase4" type="text"></td>
        <td><input id="output4" type="text"></td>
      </tr>
      <tr id="tc5" style="display: none">
        <td class="col-md-1">5</td>
        <td><input id="testcase5" type="text"></td>
        <td><input id="output5" type="text"></td>
      </tr>
      <tr>
        <td class="col-md-1"></td>
        <td><input type="button" value="add testcase" onclick="addtestcase()"></td>
        <td><input type="button" value="delete testcase" onclick="deletetestcase()"></td>
      </tr>
    </tbody>
  </table>


  <input id="newQuesBtn" class="btn btn-primary" type="submit" name="newquestion" value="submit"/>
  </form>

  <div id="output"></div>
  <div><br><br></div>
</div>
</div>

<div class="split right">
  <div class="centered">
  <!-- <div class="container" style="padding: 80px 25px 75px"> -->
    <div>
        <h2>Question Bank </h2> <br><br>
        <div class="table-responsive">
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
          <table id="bankTable" class="table table-striped">
            <thead>
              <tr>
                <th class="col-md-1">id</th>
                <th class="col-md-3">question</th>
                <th class="col-md-1">difficulty</th>
                <th class="col-md-1">category</th>
                <th class="col-md-1">test cases</th>
              </tr>
            </thead>
            <tbody id="bank">
            </tbody>
          </table>
        </div>
      </div>
      <div><br><br></div>
  <!-- </div> -->
  </div>
</div>

<script>
var testcaseCnt = 2;

// fix testcases
// use testcaseCnt (?) to get values of testcases
document.addEventListener('submit', function(e){
  e.preventDefault();
  const quesForm = e.target;
  const ques = quesForm.quesInput.value;
  const diff = quesForm.quesDifficulty.value;
  const cate = quesForm.quesCategory.value;
  const constraint = quesForm.constraint.value == ""? null : quesForm.constraint.value;
  const testCase1 = quesForm.testcase1.value;
  const output1 = quesForm.output1.value;
  const testCase2 = quesForm.testcase2.value;
  const output2 = quesForm.output2.value;
  const testCase3 = quesForm.testcase3.value==""? null : quesForm.testcase3.value;
  const output3 = quesForm.output3.value==""? null : quesForm.output3.value;
  const testCase4 = quesForm.testcase4.value==""? null : quesForm.testcase4.value;
  const output4 = quesForm.output4.value==""? null : quesForm.output4.value;
  const testCase5 = quesForm.testcase5.value==""? null : quesForm.testcase5.value;
  const output5 = quesForm.output5.value==""? null : quesForm.output5.value;
  const quesObj = {
    "question": ques,
    "difficulty": diff,
    "category": cate,
    "constraint": constraint,
    "testcase1": testCase1,
    "output1": output1,
    "testcase2": testCase2,
    "output2": output2,
    "testcase3": testCase3,
    "output3": output3,
    "testcase4": testCase4,
    "output4": output4,
    "testcase5": testCase5,
    "output5": output5
  };
  // alert(JSON.stringify(quesObj));

  fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/inputnewques.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json"
  },
    body: JSON.stringify(quesObj)
  }).then(resp => resp.json()
  ).then((data) => {
    console.log(data);
    console.log(JSON.stringify(data));
    location.reload();
  });


});


let fetchreq = fetch("https://afsaccess4.njit.edu/~cth9/CS490/instructor/questionbank.php");
fetchreq.then((res) => {
  return res.json();
}).then((data) => {
  console.log(data);

  // filling table with question bank
  var questionBank = "";
  var obj = data;
  for(let i in obj) {
    questionBank += '<tr> <td class="col-md-1">' + obj[i]["id"] + '</td> <td class="col-md-3">' + obj[i]["question"] + '</td> <td class="col-md-1">' + obj[i]["difficulty"] + '</td> <td class="col-md-1">' + obj[i]["category"] + '</td> <td class="col-md-1">' + obj[i]["testcase1"] + '; ' + obj[i]["testcase2"];
    if (obj[i]["testcase3"] != null) {
      questionBank += '; ' + obj[i]["testcase3"];
    } if (obj[i]["testcase4"] != null) {
      questionBank += '; ' + obj[i]["testcase4"];
    } if (obj[i]["testcase5"] != null) {
      questionBank += '; ' + obj[i]["testcase5"];
    }
    questionBank += '</td> </tr>';

    // questionBank += questionBank;
    // questionBank += questionBank;
  }
  document.getElementById("bank").innerHTML = questionBank;
});


function keywordSearch() {
  // Declare variables
  let input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("bankTable");
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
  table = document.getElementById("bankTable");
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
  table = document.getElementById("bankTable");
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

function addtestcase() {
  if (testcaseCnt < 5) {
    testcaseCnt++;
    document.getElementById("tc" + testcaseCnt).style.display = "";
    // enable delete button
    if (testcaseCnt == 5) {
      // disable add button
    }
  }
}

function deletetestcase() {
  if (testcaseCnt > 2) {
    document.getElementById("tc"+testcaseCnt).style.display = "none";
    document.getElementById("testcase"+testcaseCnt).value = "";
    document.getElementById("output"+testcaseCnt).value = "";
    testcaseCnt--;
  }
}


</script>

</body>

</html>
