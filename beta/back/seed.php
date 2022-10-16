<?php
    // You must run this file first with a newly created database!

    // This file is only for backend dev to test the database


    // Establishing connection to SQL server
    $servername = "sql1.njit.edu";
    $username = "nk553";
    $password = "KanyeWest300*";
    $db = "nk553";
    $connect = new mysqli($servername, $username, $password, $db);
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }
    // Creating first table - not really needed
    $sql = "CREATE TABLE IF NOT EXISTS Logins (
        id INT(1) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(30) NOT NULL,
        pass VARCHAR(32) NOT NULL,
        position VARCHAR(10) NOT NULL
        )";
    $connect->query($sql);
    
    //inserting generic data
    $sql2 = "INSERT INTO Logins (user, pass, position)
    VALUES 
    ('student300', 'ad335b5876f05f53d8635267d11a3255', 'student'),
    ('student400', '489a9991d3cbdd213e6d9a956bbd69d1', 'student'),
    ('teacher200', 'f0f7816b455f0b5069f3326fc886a4be', 'teacher')";
    $connect->query($sql2);


    $sql3 = "INSERT INTO Questionbank (question, difficulty, category, testcase1, testcase2, output1, output2)
     VALUES
    ('Define hi', 'Easy', 'Loops', 'hi(1) == 0', 'hi(0) == 1', '1', '0')";
    $connect->query($sql3);
    
    $sql4 ="INSERT INTO Exam (questionId, pnts, examnum)
     VALUES
    ('20', '15', '1')";
    $connect->query($sql4);

    $sql5 ="INSERT INTO Responses (status, studentId, examQuestionId, answer, awardedpnts, comments, finalpnts) 
    VALUES
    ('published', 'student300', '20', 'def hi():\n\treturn 0', NULL, NULL, NULL)";
    $connect->query($sql5);

    $sql6 ="UPDATE Responses SET awardedpnts='0',comments='Good',finalpnts='0' WHERE studentId = 'student300'";
    $connect->query($sql6);

    echo "Seed created";

?>