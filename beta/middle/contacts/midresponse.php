<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $username = $_POST['username'];
    $examqid = $_POST['examqid'];
    $answer = $_POST['answer'];
    // url to change later
    $url = "https://afsaccess4.njit.edu/~bjb38/CS490/response.php";
    // Fields array to collect the previous information
    $fields = [
      "username" => $username,
      "examqid" => $examqid,
      "answer" => $answer,
    ];
    // Builds into a string from the array
    $fields_string = http_build_query($fields);
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    // grab URL and pass it to the browser
    $response = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);

    // Send back the full response - json as opposed to a string
    echo $response;
  }
?>