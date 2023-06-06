<!DOCTYPE html>

<html lang="en">
    <?php
    include "inc/head.inc.php";
    ?>

    <body>
        <?php
        include "inc/nav.inc.php";
        ?>

        <main>
            <div class="section carousel" id="carousel_main">
                <?php
                include "inc/carousel.inc.php";
                ?>
            </div>
            <div class="hero has-before">
                <div class="container">

                    <h1 class="h1 section-title" data-reveal="bottom">
                        User's <span class="span">Favourite</span>
                    </h1>
                    <p class="section-subtitle" data-reveal="bottom">All time favourite games that our users love!</p>
                </div>
            </div>

            <p class="section-text" data-reveal="bottom">
                Displayed below are the games with the most downloads and best ratings from our users. Perhaps you would like it too. check it out now!
            </p>
            <?php
            include "inc/index_games.inc.php";
            ?>
        </main>

        <?php
        include "inc/footer.inc.php";
        ?>

    </body>

</html>