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
    <h1>Remove Order Type</h1>
    <?php
    session_start();

    include __DIR__ . '/../config.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["orderTypeID"])) {
            $orderTypeID = $_POST["orderTypeID"];

            $sql_removeOrderType = "DELETE FROM order_type WHERE orderTypeID = '$orderTypeID';";
            if($remove = $conn->query($sql_removeOrderType) === TRUE) {
                echo "Order Type Successfully Removed";
                header("Location: ordertypes.php");
                exit();
            } else {
                echo "Error- Query Error";
            }

        } else {
            echo "Error- ID not Passed";
        } 
    } else {
        echo "Error- Form not submitted";
    }
    ?>
    
    <?php
    $conn->close();
    ?>

</body>

</html>