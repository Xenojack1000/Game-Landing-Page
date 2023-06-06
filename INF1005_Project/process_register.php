<?php include "acl/no_loggedin_only.php"; ?>
<!DOCTYPE html>

<html lang="en">

    <?php
    include "inc/head.inc.php";
    require "fn/u_fn.php";

    $user = $name = $email = $number = $pwd = $pwd_hashed = $errorMsg = "";
    $news = $promo = 0;
    $success = true;

    /*
        username
    */
    if (empty($_POST["user"])) {
        $errorMsg .= "Username is required.<br>";
        $success = false;
    } else {
        $user = sanitize_input($_POST["user"]);
        //username cannot be email, to prevent A's username to be same as B's email
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Username should not be an email.<br>";
            $success = false;
        }
    }

    /*
        name
    */
    if (empty($_POST["name"])) {
        $errorMsg .= "Name is required.<br>";
        $success = false;
    } else {
        $name = sanitize_input($_POST["name"]);
    }

    /*
        email
    */
    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["email"]);
    // Additional check to make sure e-mail address is well-formed.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }

    /*
        number
    */
    if (!empty($_POST["number"])) {
        $number = sanitize_input($_POST["number"]);
        //check if sanitized is 8-digit
        if (!preg_match('/^[0-9]{8}$/', $number)) {
            $errorMsg .= "Number must be 8-digit long.<br>";
            $success = false;
        }
    }

    /*
        password
    */
    if (empty($_POST["pwd"])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
            $errorMsg .= "Passwords don't match.<br>";
            $success = false;
        } else {
            $pwd_hashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        }
    }

    /*
        checkboxes
    */
    if (isset($_POST['news'])) {
        $news = 1;
    }
    if (isset($_POST['promo'])) {
        $promo = 1;
    }

    if ($success) {
        saveMemberToDB();
    }

    if ($success) {
        $title = "Your registration is successful!";
        $subtitle = "Thank you for signing up, " . $name . ".";
        $form = "inc/form_login.inc.php";
    } else {
        $title = "Oops!";
        $subtitle = "The following errors were detected:";
        $form = "inc/form_register.inc.php";
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
            ";

            if (!empty($errorMsg)) {
                echo "
                    <p>$errorMsg</p>
                    <p>Please try again, or <a href='login.php' class='link_underline'>login here</a> if you already have an account.</p>
                ";
            }

            include $form;
            echo "
                    </section>
                </main>
            ";

    include "inc/footer.inc.php";
    ?>
    </body>
</html>