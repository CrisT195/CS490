<?php
require 'curl_util.php';

// grade.php file is used to grade the student responses
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $response = $_POST['response'];
    $decoded_data = json_decode("$response", true, JSON_INVALID_UTF8_IGNORE);
    
    // $decoded_data = json_decode('[["unpublished","student300","16","def hi():\n\treturn 0",null,null,null],["unpublished","student300","19","def hi():\n\treturn 0",null,null,null]]');
    // $decoded_data = json_decode("[[\"unpublished\",\"student300\",\"20\",\"def test(a):\\n\\treturn not a\",\"0,12.5,0\",null,null],[\"unpublished\",\"student300\",\"16\",\"def hi(a):\\n\\treturn a\",\"5,0,12.5\",null,null],[\"unpublished\",\"student300\",\"21\",\"def hi(a):\\n\\treturn 0\",\"5,17.5,17.5\",null,null]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student400\",\"35\",\"def test(a):\\n\\treturn not a\",\"0,12.5,0\",null,null,\"1\"]]", true);
    // $decoded_data = json_decode("[[unpublished,student400,35,\"def dooble(x):\n\treturn 2*x\",\"0,12.5,0\",null,null,1,none]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student500\",\"37\",\"def hello(a):\\n\\treturn a\",\"0,12.5,0\",null,null, 1],[\"unpublished\",\"student500\",\"36\",\"def tripleIt(a):\\n\\treturn a\",\"5,0,12.5\",null,null,1],[\"unpublished\",\"student500\",\"35\",\"def doubleIt(a):\\n\\treturn 0\",\"5,17.5,17.5\",null,null,1]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student400\",\"35\",\"def dooble(x):\n\treturn 2*x\",\"5,22.5,22.5\",\"testing\",\"5,22.5,22.5,N\",\"1\",\"4,6\",\"72\",\"35\",\"50\",\"1\",\"35\",\"Write a function named doubleIt\",\"easy\",\"variables\",\"doubleIt(2)\",\"doubleIt(3)\",null,null,null,\"4\",\"6\",null,null,null,null],[\"unpublished\",\"student400\",\"36\",\"def tripleIt(x):\n\treturn 3*x\",\"5,10,0\",\"\",\"5,10,10,N\",\"1\",\"6,9\",\"73\",\"36\",\"25\",\"1\",\"36\",\"Write a function named tripleIt\",\"easy\",\"variables\",\"tripleIt(2)\",\"tripleIt(3)\",null,null,null,\"6\",\"9\",null,null,null,null],[\"unpublished\",\"student400\",\"37\",\"def hello(x):\n\treturn \"hello\" + x\",\"5,0,10\",\"\",\"5,10,10,N\",\"1\",\"helloworld,hellocs490\",\"74\",\"37\",\"25\",\"1\",\"37\",\"write hello world\",\"easy\",\"variables\",\"hello(\"world\")\",\"hello(\"cs490\")\",null,null,null,\"hello world\",\"hello cs490\",null,null,null,null]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student300\",\"41\",\"def wrongName(lst):\n\tlargest = -1\n\tfor i in lst:\n\t\tif i > largest:\n\t\t\tlargest = i\n\treturn largest\",\"0,10,10,N\",null,\"5,10,10,N\",\"3\",\"for\"]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student300\",\"41\",\"def test(a):\\n\\treturn not a\",\"0,12.5,0\",null,null,4,\"for\"]]", true);
    // $decoded_data = json_decode("[[\"unpublished\",\"student400\",\"35\",\"def dooble(x):\n\treturn 2*x\",\"5,22.5,22.5\",\"testing\",\"5,22.5,22.5,N\",\"1\",\"4,6\",\"72\",\"35\",\"50\",\"1\",\"35\",\"Write a function named doubleIt\",\"easy\",\"variables\",\"doubleIt(2)\",\"doubleIt(3)\",null,null,null,\"4\",\"6\",null,null,null,null]]", true);
    // $decoded_data = json_decode("[{\"id\":\"40\",\"status\":\"unpublished\",\"studentId\":\"student400\",\"examQuestionId\":\"40\",\"answer\":\"def operation(op, a, b):\n\tif op == \"+\":\n\t\treturn a + b\n\treturn a - b\",\"awardedpnts\":null,\"comments\":null,\"finalpnts\":null,\"exam\":\"1\",\"answeroutput\":null,\"questionId\":\"40\",\"pnts\":\"50\",\"examnum\":\"1\",\"question\":\"Function operation should perform the operation specified\nby op (+,-,*,/) on the two operands a and b and return the correct result.\",\"difficulty\":\"hard\",\"category\":\"conditionals\",\"testcase1\":\"operation(\"+\", 5, 3)\",\"testcase2\":\"operation(\"/\", 8, 2)\",\"testcase3\":\"operation(\"*\",9,2)\",\"testcase4\":\"operation(\"-\",7,2)\",\"testcase5\":\"operation(\"+\",4,3)\",\"output1\":\"8\",\"output2\":\"4\",\"output3\":\"18\",\"output4\":\"5\",\"output5\":\"7\",\"extras\":null}]", true, JSON_INVALID_UTF8_IGNORE);
    // $decoded_data = json_decode("[{\"id\":\"40\",\"status\":\"unpublished\",\"studentId\":\"student400\",\"examQuestionId\":\"40\",\"answer\":\"def operation(op, a, b):\\n\\tif op == \\\"+\\\":\\n\\t\\treturn a + b\\n\\treturn a - b\",\"awardedpnts\":null,\"comments\":null,\"finalpnts\":null,\"exam\":\"1\",\"answeroutput\":null,\"questionId\":\"40\",\"pnts\":\"50\",\"examnum\":\"1\",\"question\":\"Function operation should perform the operation specified\\nby op (+,-,*,/) on the two operands a and b and return the correct result.\",\"difficulty\":\"hard\",\"category\":\"conditionals\",\"testcase1\":\"operation(\\\"+\\\", 5, 3)\",\"testcase2\":\"operation(\\\"/\\\", 8, 2)\",\"testcase3\":\"operation(\\\"*\\\",9,2)\",\"testcase4\":\"operation(\\\"-\\\",7,2)\",\"testcase5\":\"operation(\\\"+\\\",4,3)\",\"output1\":\"8\",\"output2\":\"4\",\"output3\":\"18\",\"output4\":\"5\",\"output5\":\"7\",\"extras\":null}]", true, JSON_INVALID_UTF8_IGNORE);
    // $decoded_data = json_decode("[{\"id\":\"40\",\"status\":\"unpublished\",\"studentId\":\"student400\",\"examQuestionId\":\"40\",\"answer\":\"def operation(op, a, b):\\n\\tif op == \\\"+\\\":\\n\\t\\treturn a + b\\n\\treturn a - b\",\"awardedpnts\":\"0,N\",\"comments\":null,\"finalpnts\":\"5,9,9,9,9,9,N\",\"exam\":\"1\",\"answeroutput\":\"\",\"questionId\":\"40\",\"pnts\":\"50\",\"examnum\":\"1\",\"question\":\"Function operation should perform the operation specified\\nby op (+,-,*,/) on the two operands a and b and return the correct result.\",\"difficulty\":\"hard\",\"category\":\"conditionals\",\"testcase1\":\"operation(\\\"+\\\", 5, 3)\",\"testcase2\":\"operation(\\\"/\\\", 8, 2)\",\"testcase3\":\"operation(\\\"*\\\",9,2)\",\"testcase4\":\"operation(\\\"-\\\",7,2)\",\"testcase5\":\"operation(\\\"+\\\",4,3)\",\"output1\":\"8\",\"output2\":\"4\",\"output3\":\"18\",\"output4\":\"5\",\"output5\":\"7\",\"extras\":null},{\"id\":\"41\",\"status\":\"unpublished\",\"studentId\":\"student400\",\"examQuestionId\":\"41\",\"answer\":\"def wrongName(lst):\\n\\tmax = -1\\n\\tfor i in lst:\\n\\t\\tif i >max:\\n\\t\\t\\tmax = i\\n\\treturn max\",\"awardedpnts\":\"0,0\",\"comments\":null,\"finalpnts\":\"5,8.5,8.5,3\",\"exam\":\"1\",\"answeroutput\":\"\",\"questionId\":\"41\",\"pnts\":\"25\",\"examnum\":\"1\",\"question\":\"Function largest should iterate through the given list using a for loop and return the largest value found.\",\"difficulty\":\"medium\",\"category\":\"forloops\",\"testcase1\":\"largest([3,7,2,9,8,1])\",\"testcase2\":\"largest([3,7,2,8,1])\",\"testcase3\":null,\"testcase4\":null,\"testcase5\":null,\"output1\":\"9\",\"output2\":\"8\",\"output3\":null,\"output4\":null,\"output5\":null,\"extras\":\"for\"},{\"id\":\"51\",\"status\":\"unpublished\",\"studentId\":\"student400\",\"examQuestionId\":\"51\",\"answer\":\"def quadrupleIt(a):\\n\\treturn a * 4\",\"awardedpnts\":null,\"comments\":null,\"finalpnts\":null,\"exam\":\"1\",\"answeroutput\":null,\"questionId\":\"51\",\"pnts\":\"25\",\"examnum\":\"1\",\"question\":\"Write a function named quadrupleIt.\",\"difficulty\":\"easy\",\"category\":\"variables\",\"testcase1\":\"quadrupleIt(2)\",\"testcase2\":\"quadrupleIt(3)\",\"testcase3\":\"quadrupleIt(4)\",\"testcase4\":null,\"testcase5\":null,\"output1\":\"8\",\"output2\":\"12\",\"output3\":\"16\",\"output4\":null,\"output5\":null,\"extras\":null}]", true, JSON_INVALID_UTF8_IGNORE);

    // var_dump($decoded_data);
    // print_r($decoded_data);
    // echo count($decoded_data);
    
    $questionindex = 0;
    $questions = count($decoded_data);
    $pointsarray = [0];
    $correctpntsarray = [0];
    while ($questionindex < $questions)
    {
        $usernameindex = "studentId";
        $questionidindex = "examQuestionId";
        $examnumindex = "examnum";
        $constraintindex = "extras";
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
        "questionId" => $questionid, 
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
        // echo $fullexam["testcase1"];
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
        $preformatstr = "" . $decoded_data[$questionindex]["answer"];
        // echo  $decoded_data["answer"]."<br>";
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
        // echo "<br>".$extractedfuncname;
        // echo "<br>".$correctfuncname;
        if ($extractedfuncname != $correctfuncname)
        {
            // echo "<br>";
            // echo "inname";
            // Unequal name
            $responsestr = str_replace($extractedfuncname, $correctfuncname, $responsestr);
            array_push($pointsarray[$questionindex], 0);
        }
        else
        {
// echo "equalname"."<br>";
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
            // echo "here"."<br>";
            $correctvalue = $constraintflag ? $correctconstraintpoints : 0;
            // echo $correctvalue."<br>";
            // echo $constraintflag."<br>";
            array_push($pointsarray[$questionindex], $correctvalue);
            array_push($correctpntsarray[$questionindex], $correctconstraintpoints);

        }
        else
        {
            // echo "second"."<br>";
            $nullconstraint = "N";
            array_push($pointsarray[$questionindex], $nullconstraint);
            array_push($correctpntsarray[$questionindex], $nullconstraint);
        }
        // print_r($pointsarray[$questionindex]);
        // echo "<br>";
        // print_r($correctpntsarray[$questionindex]);
        // echo "<br>";

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
        "answeroutput" => implode("~", $answerout)
        ];
        $response = call($url, $fields);
        // echo $response;
        // echo "<br>";
        // Go to next question
        $questionindex++;
    }
    echo 1;
    // Take this echo out
    // echo "<br>";
    // print_r($pointsarray);
    
}

?>