<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ovas_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from appointment_list table
$sql = "SELECT * FROM appointment_list";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Send data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
