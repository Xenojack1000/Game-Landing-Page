<?php
include "inc/head.inc.php";
?>

<html>
    <head>
        <title>Welcome back</title> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
              integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        
        <link rel="stylesheet" href="css/main.css">
        
        <!--jQuery-->
        <script defer
                src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous">
        </script>

        <!--Bootstrap JS-->
        <script defer
                src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
                integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm"
                crossorigin="anonymous">
        </script>
        
        <script defer src="js/main.js"></script>
        
    </head>
    <body>
        <?php
           // include "inc/nav.inc.php";
        ?>
        <?php
        session_start();
        
        global $ID, $password, $errorMsg, $success;

        // Create database connection.
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],$config['password'], $config['dbname']);

        // Check connection
        if ($conn->connect_error)
        {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        }
        
        if (!isset($_POST['ID'], $_POST['password']) ){
             // Could not get the data that should have been sent.
             exit('Please fill both the ID and password fields');
        }

        
        if($stmt = $conn->prepare('SELECT ID, password FROM game.Admins WHERE ID = ?')){
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param("i", $_POST['ID']);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();                                                    
                
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($member_id, $username, $email, $password);
                $stmt->fetch();
                // Account exists, now we verify the password.
                // Note: remember to use password_hash in your registration file to store the hashed passwords.
                if (password_verify($_POST['password'], $password)) {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['email'] = $_POST['email'];
                    //$_SESSION['$member_id'] = $_POST['$member_id'];
                    echo 'Welcome ' . $_SESSION['email'] . '';
                } else {
                    // Incorrect password
                    echo $_POST['email'];
                    echo $pwd;
                echo '<div style="font-size: 20px">Username or password is incorrect</div>';
                }
            } else {
                    // Incorrect username
                echo '<div style="font-size: 20px">Username or password is incorrect</div>';
            }
            
            
            
            $stmt->close();
        }

        $conn->close();
                ?>
        <?php
      //  include "inc/footer.inc.php";
        ?>
    </body>
</html>

