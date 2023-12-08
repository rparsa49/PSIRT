<!DOCTYPE html>
<html>

<head>
    <title>PSIRT User Registration</title>
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
    <h1>PSIRT User Registration</h1>
    <?php
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $servername = "localhost";
    $username = "root";
    $password = "AlfieHershey";
    $dbname = "psirt";
    $port = "3308";

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("<p class='error'>" . "Connection Failed: " . $conn->connect_error . "</p>");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role = $_POST['role'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $userName = $_POST['userName'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $address = $_POST['address'];
        $password = $_POST['password'];
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        // Check if the email already exists
        $checkEmailStmt = $conn->prepare("SELECT userID FROM user WHERE emailAddress = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();

        if ($checkEmailResult->num_rows > 0) {
            echo "<p class='error'>User with this email already exists. Please log in.</p>";
            $checkEmailStmt->close();
            $conn->close();
            // Redirect to login page or display a login link
            header("Location: login.php");
            exit(); // Stop further execution
        }

        $checkEmailStmt->close();

        // If the email doesn't exist, proceed with registration
        $registerStmt = $conn->prepare("INSERT INTO user (firstname, lastname, type, address, phoneNumber, username, password, emailAddress, ipAddress) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $registerStmt->bind_param("sssssssss", $firstName, $lastName, $role, $address, $phoneNumber, $userName, $password, $email, $ipAddress);

        // Attempt to register
        if ($registerStmt->execute()) {
            echo "<p class='success'>User registered successfully!</p>";
            // TODO: implement that it will direct to the proper dashboard based on which role they are
            exit();
        } else {
            echo "<p class='error'>Error registering user: " . $registerStmt->error . "</p>";
        }

        $registerStmt->close();
    }

    $conn->close();
    ?>
</body>

</html>
