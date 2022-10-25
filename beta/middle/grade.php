<?php
// grade.php file is used to grade the student responses

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// collect value of input field
$response = $_POST['response'];
$decoded_data = json_decode("$response", true);
// $decoded_data = json_decode('[["unpublished","student300","16","def hi():\n\treturn 0",null,null,null],["unpublished","student300","19","def hi():\n\treturn 0",null,null,null]]');
// $decoded_data = json_decode('[[ "unpublished", "student400", "16", "def hi(a):\n\treturn a", null, null, null],["unpublished", "student400", "21", "def test(a):\n\treturn 0", null, null, null],["unpublished", "student400", "20", "def hi(a):\n\treturn not a", null, null, null]]', true);
// var_dump($decoded_data);
// print_r($decoded_data);
// echo count($decoded_data);

$questionindex = 0;
$questions = count($decoded_data);
$pointsarray = [0];
while ($questionindex < $questions) {
    $usernameindex = 1;
    $questionidindex = 2;
    $pointsarray[$questionindex] = array();
    $username = $decoded_data[$questionindex][$usernameindex];
    $questionid = $decoded_data[$questionindex][$questionidindex];
    // Turn into imported function
    // url to change later
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/fullexaminfo.php";
    // Fields array to collect the previous information
    $fields = [
        "username" => $username,
        "questionid" => $questionid
    ];
    // Builds into a string from the array
    $fields_string = http_build_query($fields);
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // grab URL and pass it to the browser
    $response = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);
    $fullexam = json_decode($response, true);
// print_r($fullexam);
    // Import end
    // Get the total number of points and take away some points to reconstruct it later
    $studentpoints = 0;
    $totalpoints = $fullexam["pnts"];

    // echo $totalpoints;

    $correctnamepoints = 5;
    $totalpoints -= $correctnamepoints;
    // echo $totalpoints;
    // Update correct function (testcase) name and the full student response string
    $i = 0;
    // Get the test case name to determine what the true name of the function is
    // Starting index to start for output and testcase
    $ti = 1;
    $oi = 1;
    $testcases = array();
    $outputs = array();
    // echo ;
    // Get the test cases and outputs
    while (!is_null($fullexam["testcase"."$ti"])) {
        array_push($testcases, $fullexam["testcase"."$ti"]);
        ++$ti;
    }
    while (!is_null($fullexam["output"."$oi"])) {
        array_push($outputs, $fullexam["output"."$oi"]);
        ++$oi;
    }

    $testcasepoints = $totalpoints / count($testcases);
// echo $testcasepoints;
    $testcasepos = [0];
    foreach($testcases as $testcase) {
        $testcasepos[$i] = strpos($testcase, "(");
        $i++;
    }
    $correctfuncname = substr($testcases[0], 0, $testcasepos[0]);
    // Check if the name of the response is the same as real name
    // Also, the string may be malformed from sql, so fix it
    $preformatstr = "".$fullexam["answer"];
    $stripnewl = str_replace("\\n","\n",$preformatstr);
    $striptabs = str_replace("\\t","\t",$stripnewl);
    // $repnewl = str_replace('\n','\n',$striptabs);
    // $reptabs = str_replace('\t','\t',$repnewl);
    //$preformatstr = addslashes("$preformatstr");
    $responsestr = $striptabs;
    // echo $fullexam["answer"];
    $spacepos = strpos($responsestr, " ");
    $namepos = $spacepos + 1;
    $parenpos = strpos($responsestr, "(");
    $namelength = $parenpos - $namepos;
    $extractedfuncname = substr($responsestr, $namepos, $namelength);
    // echo $responsestr."<br>";
    // echo $namepos."<br>";
    // echo $namelength."<br>";
    // echo $correctfuncname."<br>";
    if ($extractedfuncname != $correctfuncname) {
        // Unequal name
        $responsestr = str_replace($extractedfuncname, $correctfuncname, $responsestr);
        array_push($pointsarray[$questionindex], 0);
    } else {
        // Equal name
        // Add back the points we took
        array_push($pointsarray[$questionindex], $correctnamepoints);
        $studentpoints += $correctnamepoints;
    }

    $myfile = fopen("grade.py", "w") or die("Unable to open file!");
    // Write the python file with student response and test cases with newlines
    $functxt = $responsestr;
    $nl = "\n";
    fwrite($myfile, $functxt);
    fwrite($myfile, $nl);
    // Write the number of testcases needed
    for ($tci=0; $tci < count($testcases); $tci++) { 
        $testcasestxt = "print({$testcases[$tci]})";
        fwrite($myfile, $testcasestxt);
        fwrite($myfile, $nl);
    }    
    fclose($myfile);

    $command = 'python grade.py';
    $out = null;
    $status = null;
    $command = escapeshellcmd($command);
    exec($command, $out, $status);
    // print_r($out);
    $out_i = 0;
    foreach($out as $answer) {
        // echo $answer;
        // echo $outputs[$out_i];
        // False and True evaluate to True in php,
        // so this converts it to real true/false
        // echo $answer."<br>";
        // Maybe convert both to filter var
        if (filter_var($answer, FILTER_VALIDATE_BOOLEAN) == filter_var($outputs[$out_i], FILTER_VALIDATE_BOOLEAN)) {
            $studentpoints += $testcasepoints;
            array_push($pointsarray[$questionindex], $testcasepoints);
        }
        else {
            array_push($pointsarray[$questionindex], 0);
        }
        $out_i++;
    }
    // print_r($pointsarray[$questionindex]);
    // echo $studentpoints;
    // Turn into imported function
    // url to change later
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/awardresponsepnts.php";
    // Fields array to collect the previous information
    $fields = [
    "points" => implode(",",$pointsarray[$questionindex]),
    "studentId" =>         $username,       
    "examqid" =>         $questionid,
    ];
    // Builds into a string from the array
    $fields_string = http_build_query($fields);
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // grab URL and pass it to the browser
    $response = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);
    // End import
    // Go to next question
    $questionindex++;
}
echo 1;
// // Take this echo out
// echo "<br>";
// print_r($pointsarray); 
}

?>