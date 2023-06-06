<?php include "acl/loggedin_only.php"; ?>
<!DOCTYPE html>

<html lang="en">
    <?php
    include "inc/head.inc.php";
    require "fn/u_fn.php";

    if (isset($_SESSION['name'])) {
        $title = "Logout successful.";
        $subtitle = "See you again, " . $_SESSION['name'] . "!";
        $btn = array("index.php", "btn", "HOME");
        session_destroy();
        session_start();
    } else {
        $title = "Logout failed.";
        $subtitle = "Session information unavailable, please return to home.";
        $btn = array("index.php", "btn", "HOME");
    }
    ?>

    <body>
        <?php
        include "inc/nav.inc.php";
        ?>
        <main class='container'>
            <section id='status'>
                <?php
                echo "
                    <h1 class='h1 section-title'>$title</h1>
                    <h2 class='h2 section-title'>$subtitle</h2>
                    <div class='inline-block'>
                        <a href='$btn[0]' class='$btn[1]'>$btn[2]</a>
                    </div>
                ";
                ?>
            </section>
        </main>
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>