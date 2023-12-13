<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include __DIR__ . '/../config.php';

// Check if the comment and orderID parameters are set
if (isset($_POST['comment']) && isset($_POST['orderID'])) {
    $comment = $_POST['comment'];
    $orderID = $_POST['orderID'];
    
    // Check if the clientID is set in the session
    if (!isset($_SESSION['client_id'])) {
        echo json_encode(['error' => 'User not logged in.']);
        exit();
    }

    $userID = $_SESSION['client_id'];

    // Insert comment into the database
    $insertCommentSql = "INSERT INTO comment (comment, userID, orderID, date) 
                        VALUES ('$comment', '$userID', '$orderID', NOW())";

    if ($conn->query($insertCommentSql) === TRUE) {
        // Fetch comments after inserting
        $commentsResult = $conn->query("SELECT c.*, u.firstname, u.lastname FROM comment c
                                    INNER JOIN user u ON c.userID = u.userID
                                    WHERE c.orderID = '$orderID'");

        $comments = [];
        while ($commentRow = $commentsResult->fetch_assoc()) {
            $comments[] = [
                'firstname' => $commentRow['firstname'],
                'lastname' => $commentRow['lastname'],
                'comment' => $commentRow['comment']
            ];
        }

        echo json_encode(['success' => 'Comment added successfully.', 'comments' => $comments]);
    } else {
        echo json_encode(['error' => 'Error adding comment: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Missing parameters.']);
}

$conn->close();

?>