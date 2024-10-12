<?php
$servername = "localhost";
$username = "root";  // Default WAMP username
$password = "";      // Default WAMP password is empty
$dbname = "consumer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connectionc
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>