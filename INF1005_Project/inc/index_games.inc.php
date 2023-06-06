<!-- 
    GAMES
-->

<?php
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
    // Prepare the statement:
    $stmt_currentGames = $conn->prepare("SELECT g.*, downloads, rating_count, rating_average
                                            FROM game g
                                            LEFT OUTER JOIN (SELECT game_id, count(game_id) downloads FROM purchase_history GROUP BY game_id) AS ph
                                            ON g.game_id=ph.game_id
                                            LEFT OUTER JOIN (SELECT game_id, count(rating) rating_count, round(avg(rating),0) rating_average FROM game_review GROUP BY game_id) AS gr
                                            ON g.game_id=gr.game_id
                                            WHERE g.release_date<=CURDATE()
                                            ORDER BY downloads DESC;");
    $stmt_currentGames->execute();
    $result_currentGames = $stmt_currentGames->get_result();
    $stmt_futureGames = $conn->prepare("SELECT * FROM game WHERE release_date>CURDATE() ORDER BY release_date");
    $stmt_futureGames->execute();
    $result_futureGames = $stmt_futureGames->get_result();
    $stmt_currentGames->close();
    $stmt_futureGames->close();
    $conn->close();
    
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $success = false;
}
if (!$success) {
    var_dump($errorMsg);
}else {
    if ($result_currentGames->num_rows>0) {
?>

<!--
    #CURRENT GAMES
-->
<section class="section news" aria-label="released games" id="games_current">
    <div class="container">
        <h2 class="h2 section-title">Current Games</h2>
        <ul class="news-list">
            <?php
            while ($row = $result_currentGames->fetch_assoc()){
                if (isset($row_game)){
                    if ($row_game['game_id']==$row['game_id']) {
                        continue;
                    }
                }
                $link = "game.php?game_id=" . $row['game_id'];
                $price = ($row['price']==0) ? "FREE": "$".$row['price'];
                $downloads = (empty($row['downloads'])) ? 0 : $row['downloads'];
                if (empty($row['rating_count'])) {
                    $rating = "<p class='card-meta-text'>No reviews yet!</p>";
                } else {
                    $rating = "";
                    for ($counter = 0; $counter < 5; $counter++) {
                        $rating .= ($counter < $row['rating_average']) ?
                            "<i class='fa-solid fa-star' aria-hidden='true'></i>":
                            "<i class='fa-regular fa-star'></i>";
                    }
                    $rating .= "<p class='card-meta-text'>($row[rating_count])</p>";
                }
                echo "
                    <li data-reveal='bottom'>
                        <div class='news-card'>
                            <div class='card-picture'>
                                <figure class='card-banner img-holder' style='--width: 600; --height: 400;'>
                                    <a 
                                        href=\"javascript:window.open('https://www.youtube.com/embed/$row[youtube]?autoplay=1', 'newwindow', 'width=500,height=500')\"
                                    >
                                        <img src='assets/game/landing/$row[thumbnail]' width='600' height='400' loading='lazy' alt='$row[name] gif' class='img-cover'></a>
                                </figure>
                            </div>
                            <div class='card-content'>
                                <p class='card-tag'>$row[name]</p>
                                <ul class='card-meta-list'>
                                    <li class='card-meta-item'>
                                        <i class='fa-regular fa-calendar' aria-hidden='true'></i>
                                        First released:
                                        <time class='card-meta-text' datetime='$row[release_date]'>$row[release_date]</time>
                                    </li>
                                    <li class='card-meta-item'>
                                        <i class='fa-regular fa-credit-card' aria-hidden='true'></i>
                                        Price:
                                        <p class='card-meta-text'>$price</p>
                                    </li>
                                    <li class='card-meta-item'>
                                        <i class='fa-regular fa-user' aria-hidden='true'></i>
                                        Total downloads:
                                        <p class='card-meta-text'>$downloads</p>
                                    </li>
                                    <li class='card-meta-item'>
                                        Player rating:
                                        $rating
                                    </li>
                                </ul>
                                <hr style='border:3px solid #9841ff'>
                                <h3 class='h3'>
                                    <a href='$link' class='card-title'>$row[name]</a>
                                </h3>
                                <p class='card-text'>
                                    $row[description]
                                </p>
                                <a href='$link' target='_blank' class='link has-before'>Read More</a>
                            </div>
                        </div>
                    </li>
                ";
            }
            ?>
        </ul>
    </div>
</section>
<?php
    }
    if ($result_futureGames->num_rows>0) {
?>
<!--
    #FUTURE GAMES
-->
<section class="section news" aria-label="unreleased games" id="games_future">
    <div class="container">
        <h2 class="h2 section-title">Upcoming Games</h2>
        <ul class="news-list">
            <?php
            while ($row = $result_futureGames->fetch_assoc()){
                if (isset($row_game)){
                    if ($row_game['game_id']==$row['game_id']) {
                        continue;
                    }
                }
                $link = "game.php?game_id=" . $row['game_id'];
                $price = ($row['price']==0) ? "FREE": "$".$row['price'];
                echo "
                    <li data-reveal='bottom'>
                        <div class='news-card news-card-future'>
                            <div class='card-picture'>
                                <figure class='card-banner img-holder' style='--width: 600; --height: 400;'>
                                    <a 
                                        href=\"javascript:window.open('https://www.youtube.com/embed/$row[youtube]?autoplay=1', 'newwindow', 'width=500,height=500')\"
                                    >
                                        <img src='assets/game/landing/$row[thumbnail]' width='600' height='400' loading='lazy' alt='$row[name] gif' class='img-cover'></a>
                                </figure>
                            </div>
                            <div class='card-content'>
                                <p class='card-tag'>$row[name]</p>
                                <ul class='card-meta-list'>
                                    <li class='card-meta-item'>
                                        <i class='fa-regular fa-calendar' aria-hidden='true'></i>
                                        Release date:
                                        <time class='card-meta-text' datetime='$row[release_date]'>$row[release_date]</time>
                                    </li>
                                    <li class='card-meta-item'>
                                        <i class='fa-regular fa-credit-card' aria-hidden='true'></i>
                                        Price:
                                        <p class='card-meta-text'>$price</p>
                                    </li>
                                </ul>
                                <hr style='border:3px solid #9841ff'>
                                <h3 class='h3'>
                                    <a href='$link' class='card-title'>$row[name]</a>
                                </h3>
                                <p class='card-text'>
                                    $row[description]
                                </p>
                                <a href='$link' target='_blank' class='link has-before'>Read More</a>
                            </div>
                        </div>
                    </li>
                ";
            }
            ?>
        </ul>
    </div>
</section>
<?php
    }
}
?>