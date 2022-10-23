<?php
// This file is for the insertion of the questions in the exam table

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $servername = "sql1.njit.edu";
    $username = "nk553";
    $password = "KanyeWest300*";
    $db = "nk553";
    $connect = new mysqli($servername, $username, $password, $db);
    if ($connect->connect_error)
    {
        die("Connection failed: " . $connect->connect_error);
    }

    $questions = $_POST['questions'];
    $examnum = $_POST['examnum'];

    // $decoded_questions = json_decode($questions, true);
    // $examdata = $decoded_questions["questions"];
    $examdata = $questions;
    for ($i = 0;$i < count($examdata);$i++)
    {
        $examquestion = $examdata[$i];
        $qid = $examquestion[0];
        $pointstr = $examquestion[1];
        $sql4 = "INSERT INTO Exam (questionId, pnts, examnum)
        VALUES
        ('$qid', '$pointstr', '$examnum')";
        $connect->query($sql4);
    }
    $connect->close();
    echo 1;
}
?>
