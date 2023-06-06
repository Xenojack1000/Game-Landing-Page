<?php
if (!isset($_GET['thread_id']) || empty(intval($_GET['thread_id']))) {
    header("Location:forum.php");
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
        $valid_thread_id = intval($_GET['thread_id']);
        //connect to db
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        if (isset($_POST['create_comment'], $_SESSION['account_id']) && !empty($_POST['create_comment'])) {
            $valid_id = $_SESSION['account_id'];
            $valid_comment = (isset($_POST['create_comment'])) ? sanitize_input($_POST['create_comment']) : "";
            $stmt_create = $conn->prepare("INSERT INTO forum_sub values (?, ?, ?, current_timestamp())");
            $stmt_create->bind_param("iis", $valid_id, $valid_thread_id, $valid_comment);
            $stmt_create->execute();
            $stmt_create->close();
        }

        $stmt_main = $conn->prepare("select m.*, a.p, a.u
                                        from forum_main m
                                        left outer join (select account_id, picture p, username u from account_details group by account_id)
                                        as a on m.account_id=a.account_id
                                        where thread_id=?
                                        ");
        $stmt_main->bind_param("i", $valid_thread_id);
        $stmt_main->execute();
        $result_main = $stmt_main->get_result();
        $stmt_main->close();

        $query_sub = "select s.*, a.p, a.u
                            from forum_sub s
                            left outer join (select account_id, picture p, username u from account_details group by account_id)
                            as a on s.account_id=a.account_id 
                            where thread_id=? order by date
        ";

        $query_sub .= (isset($_GET['order_by']) && $_GET['order_by'] == 1) ? "asc" : "desc";

        // Prepare the statement:
        $stmt_sub = $conn->prepare($query_sub);
        $stmt_sub->bind_param("i", $valid_thread_id);
        $stmt_sub->execute();
        $result_sub = $stmt_sub->get_result();
        $stmt_sub->close();
        $conn->close();
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
    }
    ?>

    <body id="top">
        <?php
        include "inc/nav.inc.php";
        ?>

        <main>
            <div class="hero has-before" id="forum_title">
                <div class="container">

                    <h1 class="h1 section-title" style="padding-bottom: 100px;" data-reveal="bottom">
                        Community <span class="span">Forum</span>
                    </h1>
                    <img src="assets/images/hero-banner-bg.png" alt="" class="hero-banner-bg">

                </div>
            </div>
            <?php
            if ($result_main->num_rows > 0) {
                $row_main = $result_main->fetch_assoc();
                echo "
                        <section id='forum_poster'>
                            <div class='container position-relative'>
                                <h2 class='h2 section-title'>$row_main[title]</h2>
                                <p>$row_main[text]</p>
                                <div class='forum_poster_info'>
                                    <img src='assets/acc_pic/$row_main[p]' alt='$row_main[u] pfp' class='forum-sub-main-img'>
                                    $row_main[u]
                                    <p class='forum-date-posted'>Posted on $row_main[date]</p>
                                </div>
                            </div>
                        </section>
                    ";
            }
            ?>

            <div class="section section-forum">
                <div class="container">
                    <div class="main-body p-0">
                        <div class="inner-wrapper">
                            <div class="inner-main-header">
                                <a class='btn-link' href='forum.php'>&#60; Back</a>
                                <form action='#' method='get' id='comment_order'></form>
                                <input class='d-none' name='thread_id' value='<?php echo $valid_thread_id; ?>' form='comment_order'>
                                <select class="custom-select custom-select-sm w-auto mr-1" aria-label="order by" form='comment_order' name='order_by'>
                                    <option value="0" selected>Newest Comment</option>
                                    <option value="1">Oldest Comment</option>
                                </select>
                                <button type='submit' class='btn-default' form='comment_order'>Go</button>
                            </div>
                            <?php if (isset($_SESSION['account_id'])) { ?>
                                <button class="fa-solid fa-plus" type="button" data-toggle="modal" data-target="#threadModal" id='forum_create_btn' title='create thread'></button>
                            <?php } ?>
                            <div class='inner-main-body p-2 p-sm-3 collapse forum-content show'>
                                <?php
                                if ($result_main->num_rows == 0) {
                                    echo "<h2 class='h2'>Invalid thread ID</h2>";
                                } else if ($result_sub->num_rows == 0) {
                                    echo "<h2 class='h2'>Be the first to comment!</h2>";
                                }
                                while ($row = $result_sub->fetch_assoc()) {
                                    echo "
                                        <div class='card mb-2'>
                                            <div class='card-body p-2 p-sm-3'>
                                                <div class='media forum-item'>
                                                    <div>
                                                        <a href='assets/acc_pic/$row[p]'><img src='assets/acc_pic/$row[p]' alt='User' class='rounded-circle'></a>
                                                        <span class='text-dark'>$row[u]</span>
                                                    </div>
                                                        <div class='media-body'>
                                                        <p class='text-secondary'>
                                                            $row[text]
                                                        </p>
                                                    </div>
                                                    <div class='text-muted text-center align-self-center'>";
                                    echo "
                                                        <span class='forum-date-posted' style='word-spacing: 70px'>Posted $row[date]</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ";
                                }
                                ?>
                            </div>
                        </div>

                        <!-- New Thread Modal -->
                        <div class="modal fade" id="threadModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="#" method="post">
                                        <div class="modal-header align-items-center bg-dark text-white">
                                            <span class="modal-title mb-0">Comment</span>
                                            <button type="button" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="btn-close btn-close-white">×</i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea class="form-control" name='create_comment' placeholder='Comment' maxlength="255" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-light" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-primary">Post</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php
        include "inc/footer.inc.php";
        ?>
        <script>
            $(document).ready(function() {
                setPageTitle("<?php echo isset($row_main['title']) ? "$row_main[title]" : "Oops!" ?>");
            });
        </script>

    </body>

</html>