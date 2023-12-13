<?php
$servername = "localhost";
$username = "root";
$password = "mudkip";
$database = "psirt";
$port = "3308";

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("<p style='color:red'>" . "Connection Failed: " . $conn->connect_error . "</p>");
}
?>