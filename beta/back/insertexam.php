<?php
// This file is for the insertion of the exam table


// TODO: The actual values will need to be inserted
    // This block gets the correct examnum
    $sql12 = "SELECT * FROM Exam";
    $result = $connect->query($sql12);
    if ($result->num_rows <= 3 ) {
        $sql4 ="INSERT INTO Exam (questionId, pnts, testcase1, testcase2, output1, output2, examnum)
     VALUES
    ('1', '15', 'hi(1) == 0', 'hi(2) == 1', '1', '0', '1')";
    $connect->query($sql4);
    }
     else if ($result->num_rows > 3 ) {
        $sqlc1 ="SELECT * FROM Exam ORDER BY ID DESC LIMIT 3";
       $examarr = $connect->query($sqlc1);
       $rows = $examarr->fetch_all();
       // This will return the highest id first
       // 7 is the exam num
       if ($rows[0][7] == $rows[1][7] && $rows[1][7] == $rows[2][7]) {
// All equal, so the examnum is all the same, create a new one
        $examnum = $rows[0][7]+1;
        $sql4 ="INSERT INTO Exam (questionId, pnts, testcase1, testcase2, output1, output2, examnum)
     VALUES
    ('1', '15', 'hi(1) == 0', 'hi(2) == 1', '1', '0', '$examnum')";
    $connect->query($sql4);
       }
       // They are not the same, so the exam num is still not done
       else if ($rows[0][7] != $rows[1][7] || $rows[1][7] != $rows[2][7]) {
        // Examnum is not the same, so we still have a bit more to insert
        $examnum = $rows[0][7];
        $sql4 ="INSERT INTO Exam (questionId, pnts, testcase1, testcase2, output1, output2, examnum)
     VALUES
    ('1', '15', 'hi(1) == 0', 'hi(2) == 1', '1', '0', '$examnum')";
    $connect->query($sql4);
    }
    }

?>