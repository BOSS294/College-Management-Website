<?php

// Database configuration
$servername = ""; // Change this to your MySQL host
$username = ""; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$database = ""; // Change this to your MySQL database name


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Include security functions
include 'security_functions.php';

?>

