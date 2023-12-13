<?php
include __DIR__ . '/../config.php';

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    // Fetch comments
    $commentsSql = "SELECT c.*, u.firstname, u.lastname FROM comment c
                    INNER JOIN user u ON c.userID = u.userID
                    WHERE c.orderID = '$orderID'";
    $commentsResult = $conn->query($commentsSql);

    if ($commentsResult->num_rows > 0) {
        while ($commentRow = $commentsResult->fetch_assoc()) {
            echo "<div class='comment'>";
            echo "<strong>" . $commentRow['firstname'] . " " . $commentRow['lastname'] . ":</strong>" . " ";
            echo $commentRow['comment'];
            echo "</div>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
} else {
    echo "<p>Missing orderID parameter.</p>";
}

$conn->close();
