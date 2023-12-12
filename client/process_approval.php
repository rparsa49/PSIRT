<!DOCTYPE html>
<html>

<head>
    <title>Approval Status - PSIRT Client Dashboard</title>
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

        p {
            color: #4caf50;
            font-size: 18px;
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        a.button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>Approval Status</h1>

    <?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "AlfieHershey";
    $dbname = "psirt";
    $port = "3308";

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("<p style='color:red'>" . "Connection Failed: " . $conn->connect_error . "</p>");
    }

    // Check if the orderID and decision parameters are set
    if (isset($_GET['orderID']) && isset($_GET['decision']) && isset($_GET['sitterID'])) {
        $orderID = $_GET['orderID'];
        $decision = $_GET['decision'];
        $sitterID = $_GET['sitterID'];

        // Fetch order details
        $orderSql = "SELECT * FROM request WHERE orderID = '$orderID'";
        $orderResult = $conn->query($orderSql);

        if ($orderResult->num_rows == 1) {
            $orderRow = $orderResult->fetch_assoc();

            // Check if the order is pending and has a sitter assigned
            if ($orderRow['sitterID'] != NULL) {
                if ($decision == 'approve') {
                    // Update order state to 'assigned'
                    $updateOrderSql = "UPDATE `order` SET state = 'assigned', sitterID = '$sitterID' WHERE orderID = '$orderID'";
                    $conn->query($updateOrderSql);

                    // Update confirm column to 1
                    $updateConfirmSql = "UPDATE `request` SET confirm = 1 WHERE orderID = '$orderID'";
                    $conn->query($updateConfirmSql);

                    echo "<p>Sitter approved successfully.</p>";
                } elseif ($decision == 'deny') {
                    $updateConfirmSql = "UPDATE `request` SET sitterID = NULL, confirm = 0 WHERE orderID = '$orderID'";
                    $conn->query($updateConfirmSql);

                    echo "<p>Sitter denied successfully.</p>";
                } else {
                    echo "<p style='color:red'>Invalid decision.</p>";
                }
            } else {
                echo "<p style='color:red'>Invalid order state or sitter assignment. Order State: " . $orderRow['state'] . ", Sitter ID: " . $orderRow['sitterID'] . "</p>";
            }
        } else {
            echo "<p style='color:red'>Order not found.</p>";
        }
    } else {
        echo "<p style='color:red'>Missing parameters.</p>";
    }

    $conn->close();
    ?>

    <a class="button" href="view_order.php">Back to View Orders</a>
</body>

</html>