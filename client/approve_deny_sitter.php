<!DOCTYPE html>
<html>

<head>
    <title>Approve/Deny Sitter - PSIRT Client Dashboard</title>
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

        form {
            margin-top: 20px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            padding: 15px 30px;
            margin: 10px;
            border: none;
            border-radius: 6px;
            font-size: 18px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>Approve/Deny Sitter</h1>

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

    // Fetch pending orders with assigned sitters
    $sql = "SELECT r.*, o.service, o.date FROM request r
            JOIN `order` o ON r.orderID = o.orderID
            WHERE r.clientID = '$clientID' and r.confirm = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Order Type</th><th>Order Date</th><th>Sitter Assigned</th><th>Action</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['service'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";

            // Fetch sitter details
            $sitterID = $row['sitterID'];
            $sitterSql = "SELECT * FROM user WHERE userID = '$sitterID'";
            $sitterResult = $conn->query($sitterSql);
            $sitterRow = $sitterResult->fetch_assoc();
            
            echo "<td>" . $sitterRow['firstname'] . " " . $sitterRow['lastname'] . "</td>";
            echo "<td>";
            echo "<form method='get' action='process_approval.php'>";
            echo "<input type='hidden' name='orderID' value='" . $row['orderID'] . "'>";
            echo "<input type='hidden' name='sitterID' value='" . $row['sitterID'] . "'>";
            echo "<button type='submit' name='decision' value='approve'>Approve</button>";
            echo "<button type='submit' name='decision' value='deny'>Deny</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No pending orders with assigned sitters found for the client.</p>";
    }

    $conn->close();
    ?>

    <a class="button" href="view_order.php">Back to Orders</a>
</body>

</html>