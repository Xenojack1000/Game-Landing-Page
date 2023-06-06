<!-- 
    carousel
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
    $stmt_carousel = $conn->prepare("SELECT * FROM carousel");
    $stmt_carousel->execute();
    $result_carousel = $stmt_carousel->get_result();
    $stmt_game = $conn->prepare("SELECT game_id, name FROM game");
    $stmt_game->execute();
    $result_game = $stmt_game->get_result();
    while($game = $result_game->fetch_assoc()) {
        $game_array[ $game["game_id"]] = $game;
    }
    $stmt_carousel->close();
    $stmt_game->close();
    $conn->close();
    
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $success = false;
}
if (!$success) {
    var_dump($errorMsg);
} elseif ($result_carousel->num_rows>0) {
?>

<!--- Image Carousel -->
<div class="carousel-parent-container">
    <div id="slides" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php
                $count = 0;
                while ($row = $result_carousel->fetch_assoc()) {
                    $extra_slides = ($count==0)? " class='active'" : "";
                    echo "
                        <li data-target='#slides' data-slide-to='$count' $extra_slides></li>
                    ";
                    $count += 1;
                }
                mysqli_data_seek($result_carousel, 0);
            ?>
        </ol>
        <div class="carousel-inner" role="listbox" aria-label='carousel items' aria-busy='true'>
            <?php
                $count = 0;
                while ($row = $result_carousel->fetch_assoc()) {
                    $extra_img = ($count==0)? " active" : "";
                    echo "<div class='carousel-item $extra_img'>";
                        // $game_id = $row['game_id'];
                        // $carousel_image = $row['image'];
                    echo ($row['game_id']!=0) ?
                        "<a href='game.php?game_id=$row[game_id]'>
                            <img class='d-block img-fluid' src='assets/images/carousel/$row[image]' alt='Carousel slide'>
                        </a></div>":
                        "<img class='d-block img-fluid' src='assets/images/carousel/$row[image]' alt='Carousel slide'></div>";
                    $count += 1;
                }
            ?>
        </div>
        <a class="carousel-control-prev" href="#slides" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#slides" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<?php
}
?>