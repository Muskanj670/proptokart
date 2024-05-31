<?php
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "proptokart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Additional checks for database creation
if ($conn->errno) {
    die("Database creation failed: " . $conn->error);
}
