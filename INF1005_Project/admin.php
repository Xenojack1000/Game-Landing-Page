<?php include "acl/admin_only.php"; ?>
<!DOCTYPE html>

<html lang="en">
    <?php
    include "inc/head.inc.php";
    ?>

    <body>
        <?php
        include "inc/nav.inc.php";
        ?>
        <main class='container'>
            <section id='admin-title'>
                <h1 class='h1 section-title has-before'>Admin</h1>
                <?php include "admin/message.php"; ?>
            </section>
            <section id="admin-game-table">
                <?php include "admin/crud_games.php"; ?>
            </section>
            <section id="admin-carousel-table">
                <?php include "admin/crud_carousel.php"; ?>
            </section>
        </main>
        
    <?php
    include "inc/footer.inc.php";
    ?>
    </body>
</html>