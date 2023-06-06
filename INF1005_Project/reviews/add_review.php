<?php
//only need if user is logged in
if (isset($_SESSION['account_id'])) {
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
        $valid_id = $_SESSION['account_id'];
        $valid_game_id = $row_game['game_id'];
        $stmt_get_review = $conn->prepare("SELECT * FROM game_review where account_id=? AND game_id=?");
        $stmt_get_review->bind_param("ii", $valid_id, $valid_game_id);
        $stmt_get_review->execute();
        $result_get_review = $stmt_get_review->get_result();
        $stmt_get_review->close();
        $conn->close();

        if ($result_get_review->num_rows==0) {
            $add_review_title = "Add review";
            $add_review_rating = 5;
            $add_review_text = "";
            $add_review_button = "<button type='submit' form='add_review' class='btn-success' tabindex='0'>Create</button>";
        } else {
            $row_add_review = $result_get_review->fetch_assoc();
            $add_review_title = "Edit review";
            $add_review_rating = $row_add_review['rating'];
            $add_review_text = $row_add_review['text'];
            $add_review_button = "<button type='submit' form='add_review' class='btn-primary' tabindex='0'>Edit</button>";
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
    }

    echo "
        <div id='modal_add_review' class='modal'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4 class='modal-title text-dark'>$add_review_title</h4>
                        <button type='button' class='close close_review_modal' data-dismiss='modal' tabindex='0'>&times;</button>
                    </div>
                    <div class='modal-body text-dark'>
                        <p>Tell us what you think about the game!</p>
                        <form method='POST' action='game.php?game_id=$row_game[game_id]' id='add_review'>
                            <input type='text' name='add_review_id' class='d-none' value='$valid_game_id'>
                            <div class='form-group'>
                                <input tabindex='0' required id='add_review_rating' type='number' placeholder='rating (/5)' name='add_review_rating' class='border border-dark p-3' value='$add_review_rating' min='1' max='5'>
                            </div>
                            <div class='form-group'>
                                <textarea tabindex='0' id='add_review_description' name='add_review_description' placeholder='Share your experience!' class='border border-dark p-3' maxlength='255'>$add_review_text</textarea>
                            </div>
                        </form>
                    </div>

                    <div class='modal-footer text-dark'>
                        <button type='button' class='btn-default close_review_modal' data-dismiss='modal' tabindex='0'>Cancel</button>
                        $add_review_button
                    </div>
                </div>
            </div>
        </div>

        <script>

            function add_game_review(self) {
                $('#modal_add_review').modal('show');
                document.querySelector('#modal_add_review').querySelectorAll(\"[tabindex='0']\")[0].focus();
            }

            $(document).ready(function(){
                $('.close_review_modal').on('click', function(){
                    $('#btn_add_review').focus()
                })
            });
        </script>
    ";
}
?>