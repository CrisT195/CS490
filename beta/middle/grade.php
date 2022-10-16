<?php
// Remove all of the newline echos

// You'll need to decode the json
// and use the questions length to do the loop
// and use the json values
$questionindex = 0;
$questions = 3;
$pointsarray = [0];
while ($questionindex < $questions) {
    // Get the total number of points and take away some points to reconstruct it later
    $studentpoints = 0;
    $totalpoints = 20;
    $correctnamepoints = 5;
    $totalpoints -= $correctnamepoints;
    // Update correct function (testcase) name and the full student response string
    $i = 0;
    // Get the test case name to determine what the true name of the function is
    $testcases = ["hi() == 0", "hi() == 0"];

    $testcasepoints = $totalpoints / count($testcases);

    $testcasepos = [0];
    foreach($testcases as $testcase) {
        $testcasepos[$i] = strpos($testcase, "(");
        $i++;
    }
    $correctfuncname = substr($testcases[0], 0, $testcasepos[0]);
    // Check if the name of the response is the same as real name
    $responsestr = "def hi():\n\treturn 0";
    $spacepos = strpos($responsestr, " ");
    $namepos = $spacepos + 1;
    $parenpos = strpos($responsestr, "(");
    $namelength = $parenpos - $namepos;
    $extractedfuncname = substr($responsestr, $namepos, $namelength);
    if ($extractedfuncname != $correctfuncname) {
        // Unequal
        echo 'Both strings are not equal';
        $responsestr = str_replace($extractedfuncname, $correctfuncname, $responsestr);
        echo "<br>".$responsestr;
        // Deduct 5 points
    } else {
        echo 'Both strings are equal';
        // Equal
        // Add back the points we took
        $studentpoints += $correctnamepoints;
    }

    // Take this echo out
    echo "<br>";

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
    foreach($out as $answer) {
        // False and True evaluate to True in php,
        // so this converts it to real true/false
        if (filter_var($answer, FILTER_VALIDATE_BOOLEAN)) {
            $studentpoints += $testcasepoints;
        }
    }
    print_r($out);
    echo $status;
    // Take this echo out
    echo "<br>";
    $pointsarray[$questionindex] = $studentpoints;
    echo $studentpoints;
    // Go to next question
    $questionindex++;
}
// Take this echo out
echo "<br>";
print_r($pointsarray); 
?>