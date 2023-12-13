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
    session_start();

    include 'config.php';

    if (isset($_POST['emailAddress']) && isset($_POST['password'])) {
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $emailAddress = validate($_POST['emailAddress']);
        $password = validate($_POST['password']);

        if (empty($emailAddress)) {
            header("Location: login.html?error=Email Address is required");
            exit();
        } else if (empty($password)) {
            header("Location: login.html?error=Password is required");
            exit();
        } else {
            $sql = "SELECT * FROM user WHERE emailAddress='$emailAddress' AND password='$password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $role = $row['type'];

                $_SESSION['emailAddress'] = $row['emailAddress'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['client_id'] = $row['userID'];

                switch ($role) {
                    case 'client':
                        header("Location: client/clientDash.html");
                        break;
                    case 'sitter':
                        header("Location: sitter/sitterDash.html");
                        break;
                    case 'handler':
                        header("Location: handler/handlerDash.html");
                        break;
                    default:
                        // Redirect to a default page or display an error
                        exit();
                }
                exit();
            } else {
                header("Location: login.html?error=Incorrect User name or password");
                exit();
            }
        }
    }

    $conn->close();
    echo "<br> DB Disconnect";
    ?>

</body>

</html>