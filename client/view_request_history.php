<!DOCTYPE html>
<html>

<head>
    <title>Order History - PSIRT Client Dashboard</title>
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

        p {
            margin-top: 20px;
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
    <h1>Order History</h1>

    <?php
    session_start(); // Start the session

    // Check if the clientID is set in the session
    if (!isset($_SESSION['client_id'])) {
        // Redirect to login if the clientID is not set
        header("Location: landing.php");
        exit();
    }

    $clientID = $_SESSION['client_id'];

    include __DIR__ . '/../config.php';

    // Fetch order history for the current client with completed state and archived = 1
    $sql = "SELECT o.* FROM `order` o 
            WHERE o.clientID = '$clientID' AND o.state = 'completed' AND o.archived = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Order Type</th><th>Order Date</th><th>Sitter Assigned</th><th>View Report</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['service'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";

            // Check if a sitter is assigned
            if ($row['sitterID'] == 0) {
                echo "<td>No Sitter Assigned</td>";
            } else {
                // Fetch sitter details
                $sitterID = $row['sitterID'];
                $sitterSql = "SELECT * FROM user WHERE userID = '$sitterID'";
                $sitterResult = $conn->query($sitterSql);
                $sitterRow = $sitterResult->fetch_assoc();

                echo "<td>" . $sitterRow['firstname'] . " " . $sitterRow['lastname'] . "</td>";
                echo "<td><a class='button' href='view_report.php?orderID=" . $row['orderID'] . "'>View Report</a></td>";
            }
        }

        echo "</table>";
    } else {
        echo "<p>No order history found for the client.</p>";
    }

    $conn->close();
    ?>

    <a class="button" href="clientDash.html">Back to Dashboard</a>
</body>

</html>