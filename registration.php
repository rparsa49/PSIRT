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
        ?>
    </p>
</body>
</html>