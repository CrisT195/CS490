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
    echo nl2br("Connected successfully\r\n");
    
    //creating table
    $sql = "CREATE TABLE IF NOT EXISTS Logins (
        id INT(1) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user VARCHAR(30) NOT NULL,
        pass VARCHAR(32) NOT NULL,
        position VARCHAR(10) NOT NULL
        )";
    if ($connect->query($sql) === TRUE) {
        echo nl2br("Table created successfully\r\n");
    } else {
        echo "Error creating table: " . $connect->error;
    }
    
    //inserting generic data
    $sql2 = "INSERT INTO Logins (user, pass, position)
    VALUES 
    ('student300', 'studentpassword300', 'student'),
    ('teacher200', 'teacherpassword300', 'teacher')";
    if ($connect->query($sql2) === TRUE) {
        echo nl2br("New records created successfully");
    } else {
        echo "Error: " . $connect->error;
    }

    if ($_SERVER["REQEUST_METHOD"] == "POST") {
        //passed values
        $user = $_POST['username'];
        $pass = $_POST['password'];
        
        //establishing variables
        $fields = array();
        $url = "https://afsaccess4.njit.edu/~bjb38/CS490/midlogin.php";

        //verifying records
        $sql3 = "SELECT * FROM Logins WHERE user = '$user'";
        $result = $connect->query($sql3);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (md5($row["pass"]) == $pass) {
                $fields['role'] = $row["position"];
                $fields['name'] = $row["user"];
            }
            else {
                $fields['role'] = "undefined";
                $fields['name'] = $row["user"];
            }
        }
        else {
            $fields['role'] = "undefined";
            $fields['name'] = "undefined";
        }

        //echo parsed JSON array
        $myJSON = json_encode($fields);
        echo $myJSON;

    }
    
    $connect->close();
?>