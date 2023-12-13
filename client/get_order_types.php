<?php
include __DIR__ . '/../config.php';

$result = $conn->query("SELECT * FROM order_type");

$orderTypes = [];
while ($row = $result->fetch_assoc()) {
    $orderTypes[] = $row['type_name'];
}

$conn->close();

echo json_encode($orderTypes);
?>
