<?php
//only need if user is logged in
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
    $valid_game_id = $row_game['game_id'];
    $stmt_get_reviews = $conn->prepare("SELECT gr.*, ac.username, ac.picture FROM game_review gr 
                                            LEFT OUTER JOIN (SELECT account_id, username, picture FROM account_details)
                                            AS ac ON gr.account_id=ac.account_id
                                            where game_id=?");
    $stmt_get_reviews->bind_param("i", $valid_game_id);
    $stmt_get_reviews->execute();
    $result_get_reviews = $stmt_get_reviews->get_result();
    $stmt_get_reviews->close();
    $conn->close();

    if ($result_get_reviews->num_rows==0) {
        $modal_reviews_text = "<h2 class='h2'>No reviews yet!</h2>";
    } else {
        $modal_reviews_text = "";
        while ($row_review = $result_get_reviews->fetch_assoc()) {
            $rating_tmp = "";
            for ($counter = 0; $counter < 5; $counter++) {
                $rating_tmp .= ($counter < $row_review['rating']) ?
                    "<i class='fa-solid fa-star' aria-hidden='true'></i>":
                    "<i class='fa-regular fa-star'></i>";
            }
            $modal_reviews_text .= "
                                <li class='review-list-item'>
                                    <div class='float-left align-middle review-user'>
                                        <img src='assets/acc_pic/$row_review[picture]' alt='$row_review[username] profile picture' class='review-pfp'>
                                        <span>$row_review[username]</span>
                                    </div>
                                    <div class='text-center'>
                                        <div class='review-rating'>
                                            $rating_tmp
                                        </div>
                                        <div class='review-content' tabindex='0'>
                                            <p>$row_review[text]</p>
                                        </div>
                                        <div class='review-footer'>
                                            <span class='review-date'>posted on $row_review[last_updated]</span>
                                        </div>
                                    </div>
                                </li>
                            ";
        }
    }
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $success = false;
}

echo "
    <div id='modal_view_review' class='modal'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title text-dark'>Reviews for $row_game[name]</h4>
                    <button type='button' class='close close_reviews_modal' data-dismiss='modal' tabindex='0'>&times;</button>
                </div>
                <div class='modal-body text-dark'>
                    <ul class='review-container' tabindex='0'>
                        $modal_reviews_text
                    </ul>
                </div>

                <div class='modal-footer text-dark'>
                    <button type='button' class='btn-default close_reviews_modal' data-dismiss='modal' tabindex='0'>Close</button>
                </div>
            </div>
        </div>
    </div>
";
?>

<script>
    function view_game_review(self) {
        $('#modal_view_review').modal('show');
        document.querySelector('#modal_view_review').querySelectorAll("[tabindex='0']")[0].focus();
    }

    $(document).ready(function(){
        $('.close_reviews_modal').on('click', function(){
            $('#view-all-reviews').focus()
        })
    });
</script>