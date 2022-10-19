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
</head>

<body>

<div class="container" style="padding: 80px 25px 75px">
  <h2>Add a question to the Question Bank</h2>
  <form method="POST">
    <div class="form-group">
      <label for="quesInput">Question:</label>
      <textarea class="form-control"  id="quesInput" name="quesInput" required></textarea>
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
    <label for="testcase1">Test Case 1:</label>
    <input class="form-control" id="testcase1" name="testcase1" required/>
  </div>
  <div class="form-group">
    <label for="output1">Expected Output for Test Case 1:</label>
    <input class="form-control" id="output1" name="output1" required/>
  </div>
  <div class="form-group">
    <label for="testcase2">Test Case 2:</label>
    <input class="form-control" id="testcase2" name="testcase2" required/>
  </div>
  <div class="form-group">
    <label for="output2">Expected Output for Test Case 2:</label>
    <input class="form-control" id="output2" name="output2" required/>
  </div>
  <input id="newQuesBtn" class="btn btn-primary" type="submit" name="newquestion" value="submit"/>
  <!-- <button class="btn btn-primary">Submit</button> -->
  </form>

  <div id="output"></div>

</div>

<script>

document.addEventListener('submit', function(e){
  e.preventDefault();
  const quesForm = e.target;
  const ques = quesForm.quesInput.value;
  const diff = quesForm.quesDifficulty.value;
  const cate = quesForm.quesCategory.value;
  const testCase1 = quesForm.testcase1.value;
  const output1 = quesForm.output1.value;
  const testCase2 = quesForm.testcase2.value;
  const output2 = quesForm.output2.value;
  const quesObj = {
    "question": ques,
    "difficulty": diff,
    "category": cate,
    "testcase1": testCase1,
    "output1": output1,
    "testcase2": testCase2,
    "output2": output2
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
  window.location.replace("instructorhome.php");
  });


});


</script>

</body>

</html>
