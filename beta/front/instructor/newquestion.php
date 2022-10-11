<?php
 session_start();
 if(!(isset($_SESSION["user"]) && isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == "teacher")) {
  die(header("Location: ../login.php"));
 }

?>

<!-- Page to add new question to the question bank.
TODO: send new question to middle end -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<div class="container" style="padding: 80px 25px 75px">
 <h2>Add a question to the Question Bank</h2>
 <form method="POST">
  <div class="form-group">
   <label for="quesInput">Question:</label>
   <input class="form-control"  id="quesInput" name="quesInput" required/>
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
  <input class="btn btn-primary" type="submit" name="newquestion" value="submit"/>
 </form>
 
</div>

