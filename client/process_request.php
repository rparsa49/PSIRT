<!DOCTYPE html>
<html>

<head>
    <title>PSIRT Request Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            margin: 50px;
        }

        h1 {
            color: #0066cc;
        }

        p.success {
            color: #4caf50;
        }

        p.error {
            color: #f44336;
        }
    </style>
</head>

<body>
    <h1>PSIRT Request Processing</h1>
    <?php
        session_start(); // Start the session

        // Check if the clientID is set in the session
        if (isset($_SESSION['client_id'])) {
            $clientID = $_SESSION['client_id'];

            // Check if form data is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve form data
                $orderType = $_POST['orderType'];
                $orderDate = $_POST['orderDate'];

                // Validate and sanitize input (you may need more validation)
                $orderType = htmlspecialchars($orderType);
                $orderDate = htmlspecialchars($orderDate);

                // Database connection details
                $servername = "localhost";
                $username = "root";
                $password = "AlfieHershey";
                $dbname = "psirt";
                $port = "3308";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname, $port);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare and execute SQL query to insert the order
                $state = 'pending'; // Initial state for a new order
                $archived = 0; // New order is not archived

                $insertOrderStmt = $conn->prepare("INSERT INTO `order` (service, date, state, clientID, archived) VALUES (?, ?, ?, ?, ?)");
                $insertOrderStmt->bind_param("sssis", $orderType, $orderDate, $state, $clientID, $archived);

                if ($insertOrderStmt->execute()) {
                    echo "<p class='success'>Order created successfully!</p>";
                } else {
                    echo "<p class='error'>Error creating order: " . $insertOrderStmt->error . "</p>";
                }

                $insertOrderStmt->close();
                $conn->close();
            } else {
                // Display the client ID
                echo "Client ID: $clientID";
            }
        } else {
            // Redirect to landing page if the clientID is not set
            header("Location: landing.php");
            exit();
        }
    ?>
</body>

</html>
