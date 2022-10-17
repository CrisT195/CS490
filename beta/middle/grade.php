<?php
// grade.php file is used to grade the student responses

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// collect value of input field
$response = $_POST['response'];
$decoded_data = json_decode($response);
// $decoded_data = json_decode('[["unpublished","student300","16","def hi():\n\treturn 0",null,null,null],["unpublished","student300","19","def hi():\n\treturn 0",null,null,null]]');

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
    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/fullexaminfo.php";
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

    // Import end
    // Get the total number of points and take away some points to reconstruct it later
    $studentpoints = 0;
    $totalpoints = $fullexam["pnts"];
    $correctnamepoints = 5;
    $totalpoints -= $correctnamepoints;
    // Update correct function (testcase) name and the full student response string
    $i = 0;
    // Get the test case name to determine what the true name of the function is
    $testcases = [$fullexam["testcase1"], $fullexam["testcase2"]];
    $outputs = [$fullexam["output1"], $fullexam["output2"]];

    $testcasepoints = $totalpoints / count($testcases);

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


    $spacepos = strpos($responsestr, " ");
    $namepos = $spacepos + 1;
    $parenpos = strpos($responsestr, "(");
    $namelength = $parenpos - $namepos;
    $extractedfuncname = substr($responsestr, $namepos, $namelength);
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
    $testcasestxt = ["print({$testcases[0]})", "print({$testcases[1]})"];
    fwrite($myfile, $testcasestxt[0]);
    fwrite($myfile, $nl);
    fwrite($myfile, $testcasestxt[1]);
    fclose($myfile);

    $command = 'python grade.py';
    $out = null;
    $status = null;
    $command = escapeshellcmd($command);
    exec($command, $out, $status);
    $out_i = 0;
    foreach($out as $answer) {
        // False and True evaluate to True in php,
        // so this converts it to real true/false
        // echo $answer."<br>";
        // Maybe convert both to filter var
        if (filter_var($answer, FILTER_VALIDATE_BOOLEAN) == $outputs[$out_i]) {
            $studentpoints += $testcasepoints;
            array_push($pointsarray[$questionindex], $testcasepoints);
        }
        else {
            array_push($pointsarray[$questionindex], 0);
        }
        $out_i++;
    }
    // echo $studentpoints;
    // Turn into imported function
    // url to change later
    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/awardresponsepnts.php";
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
    echo $response;
    // End import
    // Go to next question
    $questionindex++;
}
// Take this echo out
// echo "<br>";
// print_r($pointsarray); 
}

?>