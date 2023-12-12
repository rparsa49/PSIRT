<!DOCTYPE html>
<html>

<head>
    <title>View Orders - PSIRT Client Dashboard</title>
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
</head>

<body>
    <h1>View Orders and Requests</h1>

    <?php
    session_start(); // Start the session

    // Check if the clientID is set in the session
    if (!isset($_SESSION['client_id'])) {
        // Redirect to login if the clientID is not set
        header("Location: landing.php");
        exit();
    }

    $clientID = $_SESSION['client_id'];

    $servername = "localhost";
    $username = "root";
    $password = "AlfieHershey";
    $dbname = "psirt";
    $port = "3308";

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("<p style='color:red'>" . "Connection Failed: " . $conn->connect_error . "</p>");
    }

    // Fetch orders for the current client
    $orderSql = "SELECT * FROM `order` WHERE clientID = '$clientID' and state != 'completed'";
    $orderResult = $conn->query($orderSql);

    // Fetch requests for the current client
    $requestSql = "SELECT r.*, o.service, o.date, o.state FROM `request` r
                   JOIN `order` o ON r.orderID = o.orderID
                   WHERE r.clientID = '$clientID' AND r.confirm = 0";
    $requestResult = $conn->query($requestSql);

    if ($orderResult->num_rows > 0) {
        echo "<h2>Orders</h2>";
        echo "<table>";
        echo "<tr><th>Order Type</th><th>Order Date</th><th>Status</th><th>Sitter Assigned</th><th>Report</th></tr>";

        while ($row = $orderResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['service'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['state'] . "</td>";

            // Check if a sitter is assigned
            if ($row['sitterID'] == 0) {
                echo "<td>No Sitter Assigned</td>";
                echo "<td>No Report Available</td>";
            } else {
                // Fetch sitter details
                $sitterID = $row['sitterID'];
                $sitterSql = "SELECT * FROM user WHERE userID = '$sitterID'";
                $sitterResult = $conn->query($sitterSql);
                $sitterRow = $sitterResult->fetch_assoc();

                echo "<td>" . $sitterRow['firstname'] . " " . $sitterRow['lastname'] . "</td>";
                echo "<td><a class='button' href='view_report.php?orderID=" . $row['orderID'] . "'>View Report</a></td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No orders found for the client.</p>";
    }

    if ($requestResult->num_rows > 0) {
        echo "<h2>Requests</h2>";
        echo "<table>";
        echo "<tr><th>Order Type</th><th>Order Date</th><th>Status</th><th>Sitter Assigned</th><th>Handler Assigned</th><th>Action</th></tr>";

        while ($row = $requestResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['service'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['state'] . "</td>";

            // Check if a sitter is assigned
            if ($row['sitterID'] == 0) {
                echo "<td>No Sitter Assigned</td>";
                echo "<td>No Handler Assigned</td>";
                echo "<td>Nothing to do</td>";
            } else {
                // Fetch sitter details
                $sitterID = $row['sitterID'];
                $sitterSql = "SELECT * FROM user WHERE userID = '$sitterID'";
                $sitterResult = $conn->query($sitterSql);
                $sitterRow = $sitterResult->fetch_assoc();

                // Fetch handler details
                $handlerID = $row['handlerID'];
                $handlerSql = "SELECT * FROM user WHERE userID = '$handlerID'";
                $handlerResult = $conn->query($handlerSql);
                $handlerRow = $handlerResult->fetch_assoc();

                echo "<td>" . $sitterRow['firstname'] . " " . $sitterRow['lastname'] . "</td>";
                echo "<td>" . $handlerRow['firstname'] . " " . $handlerRow['lastname'] . "</td>";
                echo "<td><a class='button' href='approve_deny_sitter.php?orderID=" . $row['orderID'] . "'>Approve/Deny Sitter</a></td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No requests found for the client.</p>";
    }

    $conn->close();
    ?>

    <a class="button" href="clientDash.html">Back to Dashboard</a>
</body>

</html>