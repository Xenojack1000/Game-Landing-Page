<!DOCTYPE html>
<html lang="en">

<?php
include "inc/head.inc.php";
include_once "fn/db_loc.php";
include_once "fn/global_fn.php";
$errorMsg = "";
$success = true;
global $db_file;
// Create database connection.
$config = parse_ini_file($db_file);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$query = (isset($_SESSION['account_id'])) ?
    "SELECT * from game g WHERE g.game_id NOT IN (SELECT ph.game_id FROM purchase_history ph WHERE ph.account_id=?) order by release_date" :
    "SELECT * from game order by release_date";


try {
    //connect to db
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    // Prepare the statement:
    $stmt_currentGames = $conn->prepare($query);
    if (isset($_SESSION['account_id'])) {
        $account_id = $_SESSION['account_id'];
        $stmt_currentGames->bind_param('i', $account_id);
    }
    $stmt_currentGames->execute();
    $result_currentGames = $stmt_currentGames->get_result();
    $stmt_currentGames->close();
    $conn->close();
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $success = false;
}
if (!$success) {
    var_dump($errorMsg);
}
?>



<body>
    <?php
    include "inc/nav.inc.php";
    ?>
    <main>
        <div class="hero has-before" id="shopping_title">
            <div class="container">

                <h1 class="h1 section-title" style="padding-bottom: 100px;" data-reveal="bottom">
                    Shop our <span class="span">Games</span>
                </h1>
                <img src="assets/images/hero-banner-bg.png" alt="" class="hero-banner-bg">

            </div>
        </div>
        <!--
                - #SHOPPING
            -->
        <?php
        if (!$success) {
            var_dump($errorMsg);
        } else {
        ?>
            <div class="container">
                <div class="fade-in">
                    <section class="container content-section">
                        <div class="hero has-before">
                                <h2 class="section-subtitle" data-reveal="bottom" style="font-size: 30px">Catalogue</h2>
                        </div>
                        <div class="shop-items" data-reveal="bottom">
                            <?php
                            while ($row = $result_currentGames->fetch_assoc()) {
                                $button = (date('Y-m-d') < date('Y-m-d', strtotime($row['release_date']))) ?
                                    "<button disabled class='btn'>$row[release_date]</button>" :
                                    "<button class='btn btn-primary shop-item-button'>ADD TO CART</button>";
                                echo "
                                    <div class='shop-item'>
                                        <span class='shop-item-title'>$row[name]</span>
                                        <a href='game.php?game_id=$row[game_id]'>
                                            <img class='shop-item-image' src='assets/game/shop/$row[shop_thumbnail]' alt='$row[name] Image'>
                                        </a>

                                        <div class='shop-item-details'>
                                            <span class='shop-item-price'>\$$row[price]</span>
                                            $button
                                        </div>
                                    </div>
                                ";
                            }
                            ?>
                        </div>
                    </section>

                    <section class="container content-section">
                        <div class="hero has-before">
                            <h2 class="section-subtitle" data-reveal="bottom" style="font-size: 30px" id='shoppingcart'>Your Cart</h2>
                        </div>
                        <div class="cart-row" data-reveal="bottom">
                            <span class="cart-item cart-header cart-column">ITEM</span>
                            <span class="cart-price cart-header cart-column">PRICE</span>
                            <span class="cart-quantity cart-header cart-column">QUANTITY</span>
                        </div>
                        <div class="cart-items" data-reveal="bottom">

                        </div>
                    </section>
                </div>
                <div class="cart-total" data-reveal="bottom">
                    <strong class="cart-total-title">Total</strong>
                    <span class="cart-total-price">$0</span>
                </div>
                <button class="btn btn-primary btn-purchase" data-reveal="bottom">PURCHASE</button>
            </div>
        <?php
        }
        ?>
    </main>
<?php
include "inc/footer.inc.php";
?>
</body>

</html>