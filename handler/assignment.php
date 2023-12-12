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

        .center-table {
            margin: auto;
            width: 50%;
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
    ?>

    <table class="center-table">
        <tr>
            <th>OrderID</th>
            <th>Service</th>
            <th>Date</th>
            <th>ClientID</th>
            <th>Client Name</th>
            <th>Select a Sitter</th>
            <th>Make Assignment</th>
        </tr>

        <?php
        $sql_order = "SELECT `order`.orderID, `order`.service, `order`.date, `order`.clientID, CONCAT(user.firstname, ' ', user.lastname) AS fullname
                        FROM `order`
                        JOIN `user`
                        ON `order`.clientID = user.userID
                        WHERE `order`.sitterID IS NULL;";
        $order_result = $conn->query($sql_order);

        while ($row = $order_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["orderID"] . "</td>";
            echo "<td>" . $row["service"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["clientID"] . "</td>";
            echo "<td>" . $row["fullname"] . "</td>";
            echo "<td>";
            echo "<form action='makeassignment.php' method='post'>";
            echo "<select name='sitter_dropdown'>";

            $sql_sitters = "SELECT user.userID, CONCAT(user.firstname, ' ', user.lastname) AS sitter_fullname
                                FROM user
                                WHERE user.type = 'sitter';";
            $sitter_result = $conn->query($sql_sitters);
            while ($sitter = $sitter_result->fetch_assoc()) {
                echo "<option value='" . $sitter["userID"] ."'>" . $sitter["sitter_fullname"] . "</option>";
            }
            echo "</select>";

            echo "<input type='hidden' name='orderID' value = '" . $row["orderID"] . "'>";
            echo "<input type='hidden' name='clientID' value = '" . $row["clientID"] . "'>";

            echo "</td>";
            echo "<td>";
            echo "<input type='submit' value='Make Assignment'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php
    $conn->close();
    //echo "<br> DB Disconnect";
    ?>

</body>

</html>