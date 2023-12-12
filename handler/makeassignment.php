<!DOCTYPE html>
<html>
    <head>
    <title>PSIRT Handler Dashboard</title>
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

        section {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        select,
        textarea,
        button {
            margin-bottom: 12px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        select {
            padding: 8px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Style links as buttons */
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

        #clientID {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

    </style>
</head>

<body>
    <h1>Make Assignments</h1>
    <?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "mudkip";
    $database = "psirt";
    $port = "3308";

    $conn = new mysqli($servername, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("<p style='color:red'>" . "Connection Failed: " . $conn->connect_error . "</p>");
    }

    //echo "MySQL DB Connected successfully... <br>";
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["sitter_dropdown"])) {
            $sitterID = $_POST["sitter_dropdown"];
            $orderID = $_POST["orderID"];
            $clientID = $_POST["clientID"];
            $handlerID = $_SESSION['client_id'];

            $sql_assignment = "INSERT INTO request VALUES(null, '$orderID', '$clientID', '$sitterID', $handlerID, 0);";
            if($insert = $conn->query($sql_assignment) === TRUE) {
                $sql_order_update = "UPDATE `order`
                                        SET sitterID = '$sitterID'
                                        WHERE orderID = '$orderID';";
                $update = $conn->query($sql_order_update);
                echo "Assignment request successfully made";
                header("Location: handlerDash.html");
                exit();
            }
            else {
                echo "Error!";
            }

        } else {
            echo "Error- Dropdown not set";
        } 
    } else {
        echo "Error: Form not submitted";
    }

    ?>



    <?php
    $conn->close();
    //echo "<br> DB Disconnect";
    ?>

</body>

</html>