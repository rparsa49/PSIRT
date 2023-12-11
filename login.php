<!DOCTYPE html>
<html>
    <head>
        <title>PHP Connect MySQL Database</title>
    </head>
    <body>
        <h1>PHP Connect MySQL Database</h1>
        <p><?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "psirt";
                $port = "3306";

                $conn = new mysqli($servername, $username, $password, $database, $port);

                if ($conn->connect_error) {
                    die("<p style='color:red'>" . "Connection Failed: " . $conn->connect_error . "</p>");
                }

                
            

                echo "MySQL DB Connected successfully... <br>";


                if(!$result) {
                    echo "<br> Bummer! " . $conn->error;
                }
                else {
                    echo "<br> The result has " . $result->num_rows . " rows. <br>";
                }
                if (isset($_POST['emailAddress']) && isset($_POST['password'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $emailAddress = validate($_POST['emailAddress']);

    $password = validate($_POST['password']);

    if (empty($emailAddress)) {

        header("Location: index.php?error=Email Address is required");

        exit();

    }else if(empty($password)){

        header("Location: index.php?error=Password is required");

        exit();

    }else{

        $sql = "SELECT * FROM user WHERE emailAddress='$emailAddress' AND password='$password'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            if ($row['emailAddress'] === $emailAddress && $row['password'] === $password) {

                echo "Logged in!";

                $_SESSION['emailAddress'] = $row['emailAddress'];

                $_SESSION['firstname'] = $row['firstname'];

                $_SESSION['userid'] = $row['userid'];

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
                        // Redirect to a default page or display an error+
                        exit();
                    }

                exit();

            }else{

                header("Location: index.php?error=Incorect User name or password");

                exit();

            }

        }else{

            header("Location: index.php?error=Incorect User name or password");

            exit();

        }

    }

}else{

    

}
                $conn->close();

                echo "<br> DB Disconnect";
        ?></p>
    </body>
</html>