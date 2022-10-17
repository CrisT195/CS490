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
    $decoded_questions = json_decode($questions, true);
    $examdata = $decoded_questions["exam"];
    for ($i = 0;$i < count($examdata);$i++)
    {
        $examquestion = $examdata[$i];
        $qid = $examquestion[0];
        $pointstr = $examquestion[1];

        // This block gets the correct examnum
        $sql12 = "SELECT * FROM Exam";
        $result = $connect->query($sql12);
        if ($result->num_rows < 3)
        {
            // Examnum will always start at 1 when there are less than 3 elements
            $sql4 = "INSERT INTO Exam (questionId, pnts, examnum)
            VALUES
            ('$qid', '$pointstr', '1')";
            $connect->query($sql4);
        }
        else if ($result->num_rows >= 3)
        {
            // This will return the highest id first
            $sqlc1 = "SELECT * FROM Exam ORDER BY id DESC LIMIT 3";
            $examarr = $connect->query($sqlc1);
            $rows = $examarr->fetch_all();
            // Examnumindex is the current index the examnum is at in the database
            $examnumindex = 3;
            if ($rows[0][$examnumindex] == $rows[1][$examnumindex] && $rows[1][$examnumindex] == $rows[2][$examnumindex])
            {
                // All equal, so the examnum is all the same, create a new one
                $examnum = $rows[0][$examnumindex] + 1;
                $sql4 = "INSERT INTO Exam (questionId, pnts, examnum)
               VALUES
               ('$qid', '$pointstr', '$examnum')";
                $connect->query($sql4);

            }
            // They are not the same, so the exam num is still not done
            else if ($rows[0][$examnumindex] != $rows[1][$examnumindex] || $rows[1][$examnumindex] != $rows[2][$examnumindex])
            {

                // Examnum is not the same, so we still have a bit more to insert
                $examnum = $rows[0][$examnumindex];
                $sql4 = "INSERT INTO Exam (questionId, pnts, examnum)
               VALUES
               ('$qid', '$pointstr', '$examnum')";
                $connect->query($sql4);

            }
        }
    }
    $connect->close();
}
?>
