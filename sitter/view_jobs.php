<?php
session_start(); // Start the session

// Check if the user_id is set in the session
if (!isset($_SESSION['client_id'])) {
    // Redirect to login if the user_id is not set
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['client_id'];

include __DIR__ . '/../config.php';

// Fetch orders for the sitter with client information
$orderSql = "SELECT o.orderID, o.service, o.date, o.state, u.firstname AS client_firstname, u.lastname AS client_lastname
            FROM `order` o
            INNER JOIN user u ON o.clientID = u.userID
            WHERE o.sitterID = '$userID' and o.archived = 0";
$orderResult = $conn->query($orderSql);

if (!$orderResult) {
    die("<p style='color:red'>" . "Query Failed: " . $conn->error . "</p>");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>PSIRT Sitter - View Jobs</title>
</head>

<body>
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

        .view-button {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .view-button:hover {
            background-color: #45a049;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
    <h1>View Jobs</h1>

    <?php
    if ($orderResult->num_rows > 0) {
    ?>
        <table>
            <tr>
                <!-- <th>Order ID</th> -->
                <th>Service Type</th>
                <th>Client Name</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            while ($orderRow = $orderResult->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $orderRow['service']; ?></td>
                    <td><?php echo $orderRow['client_firstname'] . ' ' . $orderRow['client_lastname']; ?></td>
                    <td><?php echo $orderRow['date']; ?></td>
                    <td><button class="view-button" onclick="viewJob(<?php echo $orderRow['orderID']; ?>)">View Job</button></td>
                </tr>
            <?php
            }
            ?>
        </table>
    <?php
    } else {
        echo "<p>No jobs found for this sitter.</p>";
    }
    ?>

    <a class="button" href="sitterDash.html">Back to Dashboard</a>
    
    <script>
        function viewJob(orderID) {
            // Redirect to the page where you want to view the job details
            window.location.href = 'view_job_report.php?orderID=' + orderID;
        }
    </script>
</body>

</html>