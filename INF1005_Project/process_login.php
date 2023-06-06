<?php include "acl/no_loggedin_only.php"; ?>
<!DOCTYPE html>

<html lang="en">

    <?php
    include "inc/head.inc.php";
    require "fn/u_fn.php";

    $user = $pwd = $pwd_hashed = $errorMsg = "";
    $success = true;

    //required fields
    if (!isset($_POST['user'], $_POST['password']) ){
        $errorMsg .= "Please fill in the required fields.<br>";
        $success = false;
    } else {
        $user = sanitize_input($_POST['user']);
        $pwd = $_POST['password'];
    }

    if ($success) {
        authenticateUser();
    }

    if ($success) {
        $title = "Login successful!";
        $subtitle = "Welcome back, " . $_SESSION['name'] . ".";
        switch ($_SESSION['last_login']) {
            case null:
                $errorMsg = "Last logged in at: -";
                break;
            default:
                $errorMsg = "Last logged in at: " . $_SESSION['last_login'];
        }
    } else {
        $title = "Oops!";
        $subtitle = "The following errors were detected:";
    }

    ?>

    <body>
        <?php
            include "inc/nav.inc.php";
            echo "
                <main class='container'>
                    <section id='status'>
                        <h1 class='h1 section-title'>$title</h1>
                        <h2 class='h2 section-title'>$subtitle</h2>
                        <p>$errorMsg</p>
            ";

            if (!$success) {
                echo "
                    <p>Please try again, or <a href='register.php' class='link_underline'>Register here</a> if you are new here.</p>
                ";
                include 'inc/form_login.inc.php';
            } else {
                echo "<div class='inline-block'><a class='btn' href='index.php'>Return to Home</a></div>";
            }

            echo "
                    </section>
                </main>
            ";

    include "inc/footer.inc.php";
    ?>
    </body>
</html>