<!-- this will contain the php for the registration feature to communicate with the DB -->
<!DOCTYPE html>
<html>
<body>
    <!-- todo:
    1. connect to mysql database DONE!
    2. check if user with email and password combo exists currently
    2.5 if yes, then display error that says "account already exists, please log in" with button to nav to log in
    3. if doesnt exist already, then add to DB and navigate to correct screen based on user type -->
    <p>
<?php
    $txt = "Attempt to register...";
    echo $txt;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role = $_POST["role"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $userName = $_POST["userName"];
        $email = $_POST["email"];
        $phoneNumber = $_POST["phoneNumber"];
        $address = $_POST["address"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        
        // Add the rest of your form fields...

        // TODO: Validate and sanitize user inputs

        $servername = "localhost";
        $username = "root";
        $password = "AlfieHershey";
        $port = "3308";
        $dbname = "psirt";

        $conn = new mysqli($servername, $username, $password, $dbname, $port);

        if ($conn->connect_error) {
            die("<p style='color:red'>" . "Connection failed: " . $conn->connect_error . "</p>");
        }

        echo "<br> Successfully connected to DB...";

        // Check if the email already exists
        $checkEmailStmt = $conn->prepare("SELECT userID FROM user WHERE emailAddress = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();

        if ($checkEmailResult->num_rows > 0) {
            echo "<p style='color:red'>User with this email already exists. Please log in.</p>";
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

        if ($registerStmt->execute()) {
            echo "<p style='color:green'>User registered successfully!</p>";
            header("Location: landing.html");
            exit();
            // Redirect to the appropriate page based on user type
            // switch ($role) {
            //     case 'client':
            //         header("Location: client_dashboard.php");
            //         break;
            //     case 'sitter':
            //         header("Location: sitter_dashboard.php");
            //         break;
            //     case 'handler':
            //         header("Location: handler_dashboard.php");
            //         break;
            //     default:
            //         // Redirect to a default page or display an error
            //         header("Location: default_dashboard.php");
            // }
        } else {
            echo "<p style='color:red'>Error registering user: " . $registerStmt->error . "</p>";
        }

        $registerStmt->close();
        $conn->close();
    }
?>
    </p>
</body>
</html>