<?php
    // This is the main backend to sign in
    //establishing connection to SQL server
    $servername = "sql1.njit.edu";
    $username = "nk553";
    $password = "KanyeWest300*";
    $db = "nk553";
    $connect = new mysqli($servername, $username, $password, $db);
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

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
            if ($row["pass"] == $pass) {
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