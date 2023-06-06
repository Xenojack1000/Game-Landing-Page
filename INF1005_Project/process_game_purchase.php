<?php
//redirect if empty post
if (!isset($_POST['game_id'])) {
    header("Location: shopping.php");
}
?>
<!DOCTYPE html>

<html lang="en">

    <?php
    include "inc/head.inc.php";
    require "fn/global_fn.php";
    require "fn/db_loc.php";

    $user = $pwd = $pwd_hashed = $errorMsg = "";
    $success = true;

    //required fields
    if (!isset($_SESSION['account_id']) ){
        $errorMsg = "Please <a href='login.php'>login</a> to purchase games.<br>";
        $success = false;
    }

    if ($success) {
        global $db_file;
        // Create database connection.
        $config = parse_ini_file($db_file);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            //connect to db
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            $stmt_check = $conn->prepare("SELECT g.*, 
                                            CASE WHEN EXISTS (SELECT * FROM purchase_history where game_id=? AND account_id=?) THEN 1 ELSE NULL END purchase
                                            FROM game g WHERE g.game_id=?
                                        ");
            $account_id = $_SESSION['account_id'];
            $game_id = intval($_POST['game_id']);
            $stmt_check->bind_param('iii', $game_id, $account_id, $game_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $stmt_check->close();
            if ($result_check->num_rows==0) { //game does not exist
                $errorMsg = "Game $game_id does not exist";
                $success = false;
            } else {
                $row_check = $result_check->fetch_assoc();
                if ($row_check['purchase']==1) {
                    $errorMsg = "You have already purchased '$row_check[name]'.";
                    $success = false;
                }
            }
            if ($success) {
                $stmt_purchase = $conn->prepare("INSERT INTO purchase_history (account_id, game_id, date_purchase) values (?, ?, curdate())");
                $stmt_purchase->bind_param('ii', $account_id, $game_id);
                $stmt_purchase->execute();
                $stmt_purchase->close();
            }
            $conn->close();
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            $success = false;
            header("Location: shopping.php");
        }
    }

    if ($success) {
        $title = "Purchase successful!";
        $subtitle = "Hope you enjoy your new game, '$row_check[name]'.";
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
            } else {
                echo "<div class='inline-block'><a class='btn' href='shopping.php'>Continue browsing</a></div>";
            }

            echo "
                    </section>
                </main>
            ";

    include "inc/footer.inc.php";
    ?>
    </body>
</html>