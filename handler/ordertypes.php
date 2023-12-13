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
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .form-container {
            max-width: 400px;
            margin: 0 auto
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
    <h1>Manage Order Types</h1>
    <?php
    session_start();
    
    include __DIR__ . '/../config.php';
    
    ?>

    <form method="POST" action="add_ordertype.php" class="form-container">
        <label for="newOrderType"></label>
        <input type="newOrderType" id="newOrderType" name="newOrderType" placeholder="New order type" required>
        <input type="submit" value="Add">
    </form>


    <table class="center-table">
        <tr>
            <th>ID #</th>
            <th>Order Type</th>
            <th>Remove</th>
        </tr>

        <?php
        $sql_ordertype = "SELECT orderTypeID, type_name
                        FROM order_type;";
        $ordertype_result = $conn->query($sql_ordertype);

        while ($row = $ordertype_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["orderTypeID"] . "</td>";
            echo "<td>" . $row["type_name"] . "</td>";
            echo "<td>";
            echo "<form action='remove_ordertype.php' method='post'>";
            echo "<input type='hidden' name='orderTypeID' value = '" . $row["orderTypeID"] . "'>";
            echo "<input type='submit' value='Remove Order Type'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <section id="return">
        <h2><a href="handlerDash.html" class="button">Return to Dashboard</a></h2>
    </section>

    <?php
    $conn->close();
    //echo "<br> DB Disconnect";
    ?>

</body>

</html>