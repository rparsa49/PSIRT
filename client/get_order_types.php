<?php
$servername = "localhost";
$username = "root";
$password = "AlfieHershey";
$dbname = "psirt";
$port = "3308";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection Failed: ' . $conn->connect_error]));
}

$result = $conn->query("SELECT * FROM order_type");

$orderTypes = [];
while ($row = $result->fetch_assoc()) {
    $orderTypes[] = $row['type_name'];
}

$conn->close();

echo json_encode($orderTypes);
?>
