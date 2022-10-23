<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// collect value of input field
$questions = $_POST['questions'];


// Create function later
// Get the number of exams
$url = "https://afsaccess4.njit.edu/~nk553/CS490/totalnumexam.php";
// Fields array to collect the previous information
$fields = array();
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

$data = json_decode($response);
// Increase the examnum
$examnum = $data[0] + 1;
// End import
    // Make a call to the backend to get the number of exams
    // Increment by 1, send that examnum

    // url to change later
    $url = "https://afsaccess4.njit.edu/~nk553/CS490/insertexam.php";

    $fields = [
        "questions" => $questions,
        "examnum" => $examnum
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