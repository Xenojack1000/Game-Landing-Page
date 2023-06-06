<?php

require "db_loc.php";
require "global_fn.php";

/*
    Function file for user details
*/

function saveMemberToDB() {
    global $user, $name, $email, $number, $pwd_hashed, $errorMsg, $success, $news, $promo, $db_file;
    $privilege = "customer";
    // Create database connection.
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        //connect to db
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        // Prepare the statement:
        $stmt = $conn->prepare("INSERT INTO account_details (username, name, email, phone, password, privilege, newsletter, promo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Bind & execute the query statement:
        $stmt->bind_param("ssssssii", $user, $name, $email, $number, $pwd_hashed, $privilege, $news, $promo);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
    }
}

function authenticateUser() {
    global $user, $pwd_hashed, $errorMsg, $success, $db_file;
//     Create database connection.
   $config = parse_ini_file($db_file);
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   try {
        //connect to db
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM account_details WHERE email=? or username=?");
        // Bind & execute the query statement:
        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Note that email field is unique, so should only have
            // one row in the result set.
            $row = $result->fetch_assoc();
            $pwd_hashed = $row["password"];
            // Check if the password matches:
            if (!password_verify($_POST["password"], $pwd_hashed)) {
                // Don't be too specific with the error message - hackers don't
                // need to know which one they got right or wrong. :)
                $errorMsg = "Incorrect login credentials. Please try again.";
                $success = false;
            }
        } else {
            $errorMsg = "Incorrect login credentials. Please try again.";
            $success = false;
        }
        //store useful parameters in session
        if ($success) {
            foreach(array('account_id', 'username', 'name', 'privilege', 'last_login') as &$value){
                $_SESSION[$value] = $row[$value];
            }
            //update last_login
            $stmt = $conn->prepare("UPDATE account_details SET last_login=current_timestamp() WHERE account_id=?");
            $stmt->bind_param("i", $row['account_id']);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
   } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
   }
}

function getUserDetails() {
    global $user, $name, $email, $number, $last_login, $news, $promo, $picture, $errorMsg, $success, $db_file;
    // Create database connection.
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        //connect to db
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT username, name, email, phone, last_login, newsletter, promo, picture FROM account_details WHERE account_id=?");
        // Bind & execute the query statement:
        $stmt->bind_param("i", $_SESSION['account_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Note that email field is unique, so should only have
            // one row in the result set.
            $row = $result->fetch_assoc();
        } else {
            $errorMsg = "Incorrect session information. Please logout and login again.";
            $success = false;
        }
        //store useful parameters in session
        if ($success) {
            $user = $row['username'];
            $name = $row['name'];
            $email = $row['email'];
            $number = $row['phone'];
            $last_login = $row['last_login'];
            $news = ($row['newsletter'] == 1) ? true : false;
            $promo = ($row['promo'] == 1) ? true : false;
            $picture = $row['picture'];
        }
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
    }
}

function updateProfilePicture() {
    global $newImgName, $errorMsg, $success, $db_file;
    // Create database connection.
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        //connect to db
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        // Prepare the statement:
        $stmt = $conn->prepare("UPDATE account_details SET picture=? WHERE account_id=?");
        // Bind & execute the query statement:
        $stmt->bind_param("si", $newImgName, $_SESSION['account_id']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
    }

}


?>