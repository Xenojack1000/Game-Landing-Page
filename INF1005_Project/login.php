<?php include "acl/no_loggedin_only.php"; ?>
<!DOCTYPE html>

<html lang="en">
    <?php
    include "inc/head.inc.php";
    ?>

    <body>
        <?php
        include "inc/nav.inc.php";
        ?>

        <main class="container">
            <section id="login">
                <h1 class="h1 section-title">Member login</h1>
                <p>
                    For new members, please go to the
                    <a href="register.php" class="link_underline">sign up page</a>.
                </p>
                <?php
                include "inc/form_login.inc.php";
                ?>
            </section>
        </main>
        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>
