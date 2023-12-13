<?php
session_start();

// Check if the user_id is set in the session
if (!isset($_SESSION['client_id'])) {
    // Redirect to login if the user_id is not set
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['client_id'];

include __DIR__ . '/../config.php';

// Check if the orderID parameter is set
if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    // Fetch order details
    $orderSql = "SELECT o.orderID, o.service, o.date, o.state, u.address, u.phoneNumber, u.firstname AS sitter_firstname, u.lastname AS sitter_lastname
                FROM `order` o
                INNER JOIN user u ON o.clientID = u.userID
                WHERE o.orderID = '$orderID'";
    $orderResult = $conn->query($orderSql);

    if (!$orderResult) {
        die("<p style='color:red'>" . "Query Failed: " . $conn->error . "</p>");
    }

    // Fetch comments
    $commentsSql = "SELECT c.*, u.firstname, u.lastname FROM comment c
                    INNER JOIN user u ON c.userID = u.userID
                    WHERE c.orderID = '$orderID'";
    $commentsResult = $conn->query($commentsSql);
?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>PSIRT Sitter - View Job Report</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                text-align: center;
                margin: 20px;
            }

            h1 {
                color: #0066cc;
                margin-bottom: 20px;
            }

            table {
                width: 80%;
                border-collapse: collapse;
                margin: 20px auto;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #4caf50;
                color: #fff;
            }

            tr:hover {
                background-color: #f5f5f5;
            }

            .comments {
                margin-top: 20px;
                text-align: left;
            }

            .comments textarea {
                width: 100%;
                margin-bottom: 10px;
            }

            .comments .comment {
                border: 1px solid #ddd;
                padding: 10px;
                margin-bottom: 10px;
            }

            .comments form {
                display: inline-block;
                text-align: left;
            }

            .comments form button {
                background-color: #4caf50;
                color: #fff;
                cursor: pointer;
                padding: 10px 15px;
                border: none;
                border-radius: 6px;
                font-size: 14px;
            }

            .comments form button:hover {
                background-color: #45a049;
            }

            a.button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #4caf50;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
                margin-right: 10px;
            }

            a.button:hover {
                background-color: #45a049;
            }
        </style>
        <script>
            function addComment(orderID) {
                var comment = document.getElementById('commentInput').value;

                console.log(orderID);
                console.log(comment);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Update the comments section
                            commentsContainer.innerHTML = ''; // Clear existing comments
                            response.comments.forEach(function(comment) {
                                var commentDiv = document.createElement('div');
                                commentDiv.className = 'comment';
                                commentDiv.innerHTML = '<strong>' + comment.firstname + ' ' + comment.lastname + ':</strong>' + comment.comment;
                                commentsContainer.appendChild(commentDiv);
                            });

                            document.getElementById('commentInput').value = ''; // Clear the input field
                        } else {
                            alert('Error adding comment: ' + response.error);
                        }

                    }
                };

                xhr.open('POST', 'add_comments.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('comment=' + comment + '&orderID=' + orderID);
            }

            function loadComments(orderID) {
                var commentsContainer = document.getElementById('commentsContainer');

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        commentsContainer.innerHTML = xhr.responseText;
                    }
                };

                xhr.open('GET', 'load_comments.php?orderID=' + orderID, true);
                xhr.send();
            }
        </script>
    </head>

    <body>
        <?php
        if ($orderResult->num_rows == 1) {
            $orderRow = $orderResult->fetch_assoc();
        ?>
            <h1>View Job Report</h1>

            <table>
                <tr>
                    <th>Order Type</th>
                    <th>Date</th>
                    <th>Client Name</th>
                    <th>Client Address</th>
                    <th>Client Phone Number</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td><?php echo $orderRow['service']; ?></td>
                    <td><?php echo $orderRow['date']; ?></td>
                    <td><?php echo $orderRow['sitter_firstname'] . ' ' . $orderRow['sitter_lastname']; ?></td>
                    <td><?php echo $orderRow['address'] ?></td>
                    <td><?php echo $orderRow['phoneNumber'] ?></td>
                    <td><?php echo $orderRow['state']; ?></td>
                </tr>
            </table>

            <div class="comments">
                <h2>Comments</h2>
                <div id="commentsContainer">
                    <?php
                    if ($commentsResult->num_rows > 0) {
                        while ($commentRow = $commentsResult->fetch_assoc()) {
                            echo '<div class="comment"><strong>' . $commentRow['firstname'] . ' ' . $commentRow['lastname'] . ':</strong> ' . $commentRow['comment'] . '</div>';
                        }
                    } else {
                        echo '<p>No comments for this order.</p>';
                    }
                    ?>
                </div>
                <?php
                if ($orderRow['state'] != 'completed') {
                    echo '<textarea id="commentInput" placeholder="Add a comment..." rows="4"></textarea>';
                    echo '<button type="button" onclick="addComment(' . $orderID . ')">Add Comment</button>';
                }
                if ($orderRow['state'] === 'assigned') {
                    echo '<button type="button" onclick="markOrderCompleted()">Submit for Approval</button>';
                }
                ?>
            </div>

        <?php
        } else {
            echo "<p>Order not found.</p>";
        }
        ?>

        <a class="button" href="sitterDash.html">Back to Dashboard</a>

        <script>
            // Initial load of comments
            loadComments(<?php echo json_encode($orderID); ?>);

            function markOrderCompleted() {
                var orderID = <?php echo json_encode($orderID); ?>;

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Reload the page or update UI as needed
                            location.reload();
                        } else {
                            alert('Error marking order as completed: ' + response.error);
                        }
                    }
                };

                xhr.open('POST', 'mark_pending.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('orderID=' + orderID);
            }
        </script>
    </body>

    </html>

<?php
} else {
    echo "<p>Missing orderID parameter.</p>";
}
?>