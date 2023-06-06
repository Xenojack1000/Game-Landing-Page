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

        <main class = "container">
            <section id="register">
                <h1 class="h1 section-title">NEW MEMBER REGISTRATION </h1>
                <p>
                    For existing members, please go to the
                    <a href="login.php" class="link_underline">Sign In page</a>.
                </p>
                
                <?php
                include "inc/form_register.inc.php";
                ?>

            </section>
        </main>

    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>

