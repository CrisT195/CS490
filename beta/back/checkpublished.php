<?php
    // This is the file to contact to see if to sign in
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
        
        //establishing variables
        $fields = array();

        //verifying records
        
        $sql3 = "SELECT * FROM Responses WHERE status = 'published' AND studentId = '$user'";
        $result = $connect->query($sql3);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_all();
            $fields = $rows;
        }

        //echo parsed JSON array
        $myJSON = json_encode($fields);
        echo $myJSON;
    }
    $connect->close();
?>