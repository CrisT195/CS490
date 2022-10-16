<?php
// currently placeholder data. Working version will dynamically get questionbank from middle end.

$questions = array();
$questions["questions"][0]["id"] = 0;
$questions["questions"][0]["description"] = "Given a string name, e.g. 'Bob', create a function named 'hello_name' that returns a greeting of the form 'Hello Bob!'.";
$questions["questions"][0]["difficulty"] = "easy";
$questions["questions"][0]["category"] = "strings";

$questions["questions"][1]["id"] = 1;
$questions["questions"][1]["description"] = "question 2";
$questions["questions"][1]["difficulty"] = "medium";
$questions["questions"][1]["category"] = "loops";

$questions["questions"][2]["id"] = 2;
$questions["questions"][2]["description"] = "question 3";
$questions["questions"][2]["difficulty"] = "hard";
$questions["questions"][2]["category"] = "arithmetic";

$json = json_encode($questions);
echo $json;

// ex: { "questions" : [ {"id":0, "description":"Given a string name, e.g. 'Bob', create a function named 'hello_name' that returns a greeting of the form 'Hello Bob!'." , "difficulty":"easy", "category":"strings" }, {"id":1, "description":"question 2" , "difficulty":"medium", "category":"loops" }, {"id":2, "description":"question 3" , "difficulty":"hard", "category":"arithmetic" } ] }
?>
