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
            // Retrieve password from the database
            $getPasswordStmt = $conn->prepare("SELECT password FROM user WHERE emailAddress = ?");
            $getPasswordStmt->bind_param("s", $emailAddress);
            $getPasswordStmt->execute();
            $passwordResult = $getPasswordStmt->get_result();

            if ($passwordRow = $passwordResult->fetch_assoc()) {
                $storedPassword = $passwordRow['password'];

                // Check if the stored password is hashed
                if (password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
                    // If the password needs to be rehashed, rehash it and update the database
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updatePasswordStmt = $conn->prepare("UPDATE user SET password = ? WHERE emailAddress = ?");
                    $updatePasswordStmt->bind_param("ss", $newHashedPassword, $emailAddress);
                    $updatePasswordStmt->execute();
                    $updatePasswordStmt->close();

                    // Continue with login using the new hashed password
                    $storedPassword = $newHashedPassword;
                }

                // Verify the entered password against the stored password
                if (password_verify($password, $storedPassword)) {
                    // Password is correct, proceed with login
                    $getUserDataStmt = $conn->prepare("SELECT * FROM user WHERE emailAddress = ?");
                    $getUserDataStmt->bind_param("s", $emailAddress);
                    $getUserDataStmt->execute();
                    $userDataResult = $getUserDataStmt->get_result();

                    if ($userDataRow = $userDataResult->fetch_assoc()) {
                        $role = $userDataRow['type'];

                        $_SESSION['emailAddress'] = $userDataRow['emailAddress'];
                        $_SESSION['firstname'] = $userDataRow['firstname'];
                        $_SESSION['client_id'] = $userDataRow['userID'];

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
                    } else {
                        header("Location: login.html?error=Error retrieving user data");
                        exit();
                    }
                } else {
                    // Incorrect password
                    header("Location: login.html?error=Incorrect User name or password");
                    exit();
                }
            } else {
                // Email not found
                header("Location: login.html?error=Incorrect User name or password");
                exit();
            }

            $getPasswordStmt->close();
            $getUserDataStmt->close();
        }
    }

    $conn->close();
    echo "<br> DB Disconnect";
    ?>

</body>

</html>