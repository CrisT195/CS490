<?php
require 'curl_util.php';

// grade.php file is used to grade the student responses
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // collect value of input field
    $response = $_POST['response'];
    $decoded_data = json_decode("$response", true);
    
    // $decoded_data = json_decode('[["unpublished","student300","16","def hi():\n\treturn 0",null,null,null],["unpublished","student300","19","def hi():\n\treturn 0",null,null,null]]');
    // $decoded_data = json_decode("[[\"unpublished\",\"student300\",\"20\",\"def test(a):\\n\\treturn not a\",\"0,12.5,0\",null,null],[\"unpublished\",\"student300\",\"16\",\"def hi(a):\\n\\treturn a\",\"5,0,12.5\",null,null],[\"unpublished\",\"student300\",\"21\",\"def hi(a):\\n\\treturn 0\",\"5,17.5,17.5\",null,null]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student500\",\"37\",\"def hello(a):\\n\\treturn a\",\"0,12.5,0\",null,null, 1],[\"unpublished\",\"student500\",\"36\",\"def tripleIt(a):\\n\\treturn a\",\"5,0,12.5\",null,null,1],[\"unpublished\",\"student500\",\"35\",\"def doubleIt(a):\\n\\treturn 0\",\"5,17.5,17.5\",null,null,1]]", true);
    // var_dump($decoded_data);
    // print_r($decoded_data);
    // echo count($decoded_data);
    $questionindex = 0;
    $questions = count($decoded_data);
    $pointsarray = [0];
    $correctpntsarray = [0];
    while ($questionindex < $questions)
    {
        $usernameindex = 1;
        $questionidindex = 2;
        $examnumindex = 7;
        $constraintindex = 8;
        // The points the student received
        $pointsarray[$questionindex] = array();
        // The actual points to send
        $correctpntsarray[$questionindex] = array();
        $username = $decoded_data[$questionindex][$usernameindex];
        $questionid = $decoded_data[$questionindex][$questionidindex];
        $examnum = $decoded_data[$questionindex][$examnumindex];
        $constraint = $decoded_data[$questionindex][$constraintindex];
        // $constraint = "recursion";
        $url = "https://afsaccess4.njit.edu/~bjb38/CS490/fullexaminfo.php";
        // Fields array to collect the previous information
        $fields = [
        "username" => $username, 
        "questionid" => $questionid, 
        "examnum" => $examnum
        ];
        $response = call($url, $fields);
        // echo $response;
        $fullexam = json_decode($response, true);
        // print_r($fullexam);
        // echo $fullexam["id"];
        // echo "<br>";
        // Get the total number of points and take away some points to reconstruct it later
        $studentpoints = 0;
        $totalpoints = $fullexam["pnts"];

        // echo $totalpoints;
        $correctnamepoints = 5;
        $correctconstraintpoints = 3;
        array_push($correctpntsarray[$questionindex], $correctnamepoints);
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
        while (!is_null($fullexam["testcase" . "$ti"]))
        {
            array_push($testcases, $fullexam["testcase" . "$ti"]);
            ++$ti;
        }
        // print_r($testcases);
        while (!is_null($fullexam["output" . "$oi"]))
        {
            array_push($outputs, $fullexam["output" . "$oi"]);
            ++$oi;
        }

        $forcheck = "for";
        $whilecheck = "while";
        $reccheck = "recursion";
        $hasconstraint = false;
        $constraintflag = false;

        if (($constraint == $forcheck) || ($constraint == $whilecheck) || ($constraint == $reccheck))
        {
            $hasconstraint = true;
            $totalpoints -= $correctconstraintpoints;
        }
        else
        {
            $hasconstraint = false;
        }
        // echo $totalpoints;
        $testcasepoints = $totalpoints / count($testcases);
        // echo $testcasepoints;
        $testcasepos = [0];
        foreach ($testcases as $testcase)
        {
            array_push($correctpntsarray[$questionindex], $testcasepoints);
            $testcasepos[$i] = strpos($testcase, "(");
            $i++;
        }
        $correctfuncname = substr($testcases[0], 0, $testcasepos[0]);
        // Check if the name of the response is the same as real name
        // Also, the string may be malformed from sql, so fix it
        $preformatstr = "" . $fullexam["answer"];
        $stripnewl = str_replace("\\n", "\n", $preformatstr);
        $striptabs = str_replace("\\t", "\t", $stripnewl);
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
        if ($extractedfuncname != $correctfuncname)
        {
            // Unequal name
            $responsestr = str_replace($extractedfuncname, $correctfuncname, $responsestr);
            array_push($pointsarray[$questionindex], 0);
        }
        else
        {
            // Equal name
            // Add back the points we took
            array_push($pointsarray[$questionindex], $correctnamepoints);
            $studentpoints += $correctnamepoints;
        }

        if ($constraint == $forcheck)
        {
            $pos = strpos($responsestr, $forcheck);
            $constraintflag = ($pos !== false);
        }
        else if ($constraint == $whilecheck)
        {
            $pos = strpos($responsestr, $whilecheck);
            $constraintflag = ($pos !== false);
        }
        else if ($constraint == $reccheck)
        {
            $count = substr_count($responsestr, $correctfuncname);
            $constraintflag = ($count >= 2);
        }
        // echo $responsestr."<br>";
        

        $myfile = fopen("grade.py", "w") or die("Unable to open file!");
        // Write the python file with student response and test cases with newlines
        $functxt = $responsestr;
        $nl = "\n";
        fwrite($myfile, $functxt);
        fwrite($myfile, $nl);
        // Write the number of testcases needed
        for ($tci = 0;$tci < count($testcases);$tci++)
        {
            $testcasestxt = "print({$testcases[$tci]})";
            // echo "$testcasestxt";
            fwrite($myfile, $testcasestxt);
            fwrite($myfile, $nl);
        }
        fclose($myfile);
        // echo grade.py
        $command = 'python grade.py';
        $out = null;
        $status = null;
        $command = escapeshellcmd($command);
        exec($command, $out, $status);
        // print_r($out);
        $out_i = 0;
        $answerout = array();
        foreach ($out as $answer)
        {
            array_push($answerout, $answer);
            // echo $answer;
            // echo $outputs[$out_i];
            // False and True evaluate to True in php,
            // so this converts it to real true/false
            // echo $answer."<br>";
            // Maybe convert both to filter var
            if ($answer == $outputs[$out_i])
            {
                $studentpoints += $testcasepoints;
                array_push($pointsarray[$questionindex], $testcasepoints);
            }
            else
            {
                array_push($pointsarray[$questionindex], 0);
            }
            $out_i++;
        }

        if ($hasconstraint)
        {
            $correctvalue = $constraintflag ? $correctconstraintpoints : 0;
            array_push($pointsarray[$questionindex], $correctvalue);
            array_push($correctpntsarray[$questionindex], $correctconstraintpoints);

        }
        else
        {
            $nullconstraint = "N";
            array_push($pointsarray[$questionindex], $nullconstraint);
            array_push($correctpntsarray[$questionindex], $nullconstraint);
        }
        // print_r($pointsarray[$questionindex]);
        // print_r($correctpntsarray[$questionindex]);
        // print_r($answerout);
        // echo $studentpoints;
        // echo "<br>";

        $url = "https://afsaccess4.njit.edu/~bjb38/CS490/awardresponsepnts.php";
        // Fields array to collect the previous information
        $fields = [
        "points" => implode(",", $pointsarray[$questionindex]),
        "studentId" => $username,
        "examqid" => $questionid,
        "allocated" => implode(",", $correctpntsarray[$questionindex]), 
        "examnum" => $examnum, 
        "answeroutput" => implode(",", $answerout)
        ];
        $response = call($url, $fields);
        // Go to next question
        $questionindex++;
    }
    echo 1;
    // Take this echo out
    // echo "<br>";
    // print_r($pointsarray);
    
}

?>