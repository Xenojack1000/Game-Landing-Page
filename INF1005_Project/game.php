<?php
if (!isset($_GET['game_id'])) {
    header("Location: shopping.php");
}
?>

<!DOCTYPE html>

<html lang="en">
<?php
include "inc/head.inc.php";
require_once "fn/db_loc.php";
require_once "fn/global_fn.php";
$errorMsg = "";
$success = true;
global $db_file;
// Create database connection.
$config = parse_ini_file($db_file);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    //connect to db
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    //if POST is to add/update review
    if (isset($_POST['add_review_id'])){
        $add_review_game_id = intval($_POST['add_review_id']);
        $add_review_account_id = $_SESSION['account_id'];
        $add_review_rating = intval($_POST['add_review_rating']);
        $add_review_description = sanitize_input($_POST['add_review_description']);
        if (0<$add_review_rating&&$add_review_rating<6) {
            $stmt_check_review = $conn->prepare("SELECT * FROM game_review WHERE account_id=? AND game_id=?");
            $stmt_check_review->bind_param("ii",$add_review_account_id,$add_review_game_id);
            $stmt_check_review->execute();
            if (($stmt_check_review->get_result()->num_rows)==0) {
                $stmt_add_review = $conn->prepare("INSERT INTO game_review values (?,?,?,?,current_timestamp())");
                $stmt_add_review->bind_param("iiis", $add_review_account_id, $add_review_game_id, $add_review_rating, $add_review_description);
            } else {
                $stmt_add_review = $conn->prepare("UPDATE game_review SET rating=?,text=?,last_updated=current_timestamp() WHERE account_id=? AND game_id=?");
                $stmt_add_review->bind_param("isii", $add_review_rating, $add_review_description, $add_review_account_id, $add_review_game_id);
            }
            $stmt_add_review->execute();
            $stmt_check_review->close();
            $stmt_add_review->close();
        }
    }

    $query = (isset($_SESSION['account_id'])) ?
        "SELECT g.*, 
            case when exists (select * from purchase_history where account_id=? and game_id=?) then 1 else null end purchase, 
            case when exists (select * from game_review where account_id=? and game_id=?) then 1 else null end review,
            downloads, rating_count, rating_average
            from game g 
            LEFT OUTER JOIN (SELECT game_id, count(game_id) downloads FROM purchase_history GROUP BY game_id) AS ph
            ON g.game_id=ph.game_id
            LEFT OUTER JOIN (SELECT game_id, count(rating) rating_count, round(avg(rating),2) rating_average FROM game_review GROUP BY game_id) AS gr
            ON g.game_id=gr.game_id
            where g.game_id=?":
        "SELECT g.*, downloads, rating_count, rating_average
            FROM game g
            LEFT OUTER JOIN (SELECT game_id, count(game_id) downloads FROM purchase_history GROUP BY game_id) AS ph
            ON g.game_id=ph.game_id
            LEFT OUTER JOIN (SELECT game_id, count(rating) rating_count, round(avg(rating),2) rating_average FROM game_review GROUP BY game_id) AS gr
            ON g.game_id=gr.game_id
            WHERE g.game_id=?";

    // Prepare the statement:
    $stmt = $conn->prepare($query);
    $game_id = intval($_GET['game_id']);
    if (isset($_SESSION['account_id'])) {
        $account_id = $_SESSION['account_id'];
        $stmt->bind_param('iiiii', $account_id, $game_id, $account_id, $game_id, $game_id);
    } else {
        $stmt->bind_param('i', $game_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $success = false;
}
?>

<body>
    <?php
    include "inc/nav.inc.php";
    echo "<main>";
    if ($result->num_rows==0) {
        echo "
            <section>
                <h1 class='h1 section-title'>Sorry, game does not exist.</h1>
                <h2 class='h2 section-title'><a href='shopping.php'>Browse available games</a></h2>
            </section>";
    } else {
        $row_game = $result->fetch_assoc();
        $download = (empty($row_game['downloads'])) ?
                    0:
                    "$row_game[downloads]";
        $rating = (empty($row_game['rating_count'])) ?
                    "No reviews yet!":
                    "$row_game[rating_average] (<button class='btn-link' onclick='view_game_review()' id='view-all-reviews'>$row_game[rating_count]</button> reviewers)";
        echo "
            <div class='videoh'>
                    <h1 class='h1 section-title' data-reveal='bottom'>$row_game[name]</h1>
                    <video class='video-bg' autoplay muted loop>
                        <source src='assets/game/video/$row_game[video]' type='video/mp4'>
                    </video>
            </div>
            <section class='intro'>
                <div>
                    <h2 class='h3'>Downloads: $download</h2>
                    <h2 class='h3'>Rating: $rating</h2>
                </div>
                <div class='row'>
                    <div class='col-sm'>
                        <h2 class='h2'>OVERVIEW</h2>
                        <p>$row_game[description]</p>
                    </div>
                </div>
                <div class='utubecontainer'>
                    <iframe width='560' height='315' src='https://www.youtube.com/embed/$row_game[youtube]' title='YouTube video player' allow='accelerometer; autoplay; clipboard-write; 
                                encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe>
                </div>


                <div class='row'>
                    <div class='col-sm'>
                        <h2 class='h2'>GAMEPLAY</h2>
                        <p>$row_game[gameplay]</p>
                    </div>
                </div>
        ";
        if (date('Y-m-d')<date('Y-m-d', strtotime($row_game['release_date']))){
            $action = "<button disabled class='btn'>Game releases on $row_game[release_date]</button>";
        } else {
            if (!isset($_SESSION['account_id'])) {
                $action = "<a href='login.php' class='btn'>Login to purchase</a>";
            } else {
            $action = (!empty($row_game['purchase'])) ?
            "<button class='btn' onclick='add_game_review()' id='btn_add_review'>Leave a review</button>":
                "
                    <div class='row'>
                        <form id='form_purchase_game' action='process_game_purchase.php' method='post'>
                            <input name='game_id' class='d-none' value='$row_game[game_id]'>
                            <button type='submit' class='btn'>Purchase game at \$$row_game[price]</button>
                        </form>
                    </div>
                ";
            }
        }
        echo "
            $action
            </section>
        ";
    }
    include "inc/index_games.inc.php";
    include "inc/footer.inc.php";
    include "reviews/add_review.php";
    include "reviews/view_review.php";
    ?>
    </main>
    <script>
        $(document).ready(function() {
            setPageTitle("<?php echo isset($row_game['name'])? "$row_game[name] - Details":"Oops!" ?>");
        });
    </script>
</body>

</html>