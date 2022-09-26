<?php
    //establishing connection to SQL server
    $servername = "sql1.njit.edu";
    $username = "nk553";
    $password = "KanyeWest300*";
    $db = "nk553";
    $connect = new mysqli($servername, $username, $password, $db);
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }
    
    //creating table
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
    ('student300', 'studentpassword300', 'student'),
    ('teacher200', 'teacherpassword300', 'teacher')";
    $connect->query($sql2);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //passed values
        $user = $_POST['username'];
        $pass = $_POST['password'];
        
        //establishing variables
        $fields = array();

        //verifying records
        $sql3 = "SELECT * FROM Logins WHERE user = '$user'";
        $result = $connect->query($sql3);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (md5($row["pass"]) == $pass) {
                $fields['role'] = $row["position"];
                $fields['name'] = $row["user"];
                echo $fields;
            }
            else {
                $fields['role'] = "undefined";
                $fields['name'] = $row["user"];
                echo $fields;
            }
        }
        else {
            $fields['role'] = "undefined";
            $fields['name'] = "undefined";
            echo $fields;
        }

        //echo parsed JSON array
        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>