<?php
session_start();

include __DIR__ . '/../config.php';

// Check if the orderID parameter is set
if (isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];

    // Check if the clientID is set in the session
    if (!isset($_SESSION['client_id'])) {
        echo json_encode(['error' => 'User not logged in.']);
        exit();
    }

    // Check if the order is in 'pending confirmation' state
    $checkStateSql = "SELECT state FROM `order` WHERE orderID = '$orderID' AND state = 'assigned'";
    $checkStateResult = $conn->query($checkStateSql);

    if ($checkStateResult->num_rows == 1) {
        // Update the order state to 'completed'
        $updateStateSql = "UPDATE `order` SET state = 'completed', archived = 1 WHERE orderID = '$orderID'";

        if ($conn->query($updateStateSql) === TRUE) {
            echo json_encode(['success' => 'Order marked as completed successfully.']);
        } else {
            echo json_encode(['error' => 'Error marking order as completed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Order is not in the required state for completion.']);
    }
} else {
    echo json_encode(['error' => 'Missing orderID parameter.']);
}

$conn->close();
