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
    if (isset($_POST['create_thread_title'], $_SESSION['account_id'])) {
        $valid_id = $_SESSION['account_id'];
        $valid_title = sanitize_input($_POST['create_thread_title']);
        $valid_description = (isset($_POST['create_thread_description'])) ? sanitize_input($_POST['create_thread_description']) : "";
        $stmt_create = $conn->prepare("INSERT INTO forum_main values (null, ?, ?, ?, current_timestamp())");
        $stmt_create->bind_param("iss", $valid_id, $valid_title, $valid_description);
        $stmt_create->execute();
        $stmt_create->close();
    }

    $base_query = "select m.*, s.md, s.ct, a.p, a.u, f.*
                        from forum_main m
                        left outer join (select distinct s.account_id a_id2,s.thread_id,md, ct 
                            from forum_sub s, (select thread_id tid, max(date) md, count(thread_id) ct From forum_sub group by tid) d 
                            where tid=s.thread_id and s.date=d.md order by s.thread_id
                        ) as s on m.thread_id=s.thread_id
                        left outer join (select account_id, picture p, username u from account_details group by account_id)
                        as a on m.account_id=a.account_id
                        left outer join (select account_id a_id2, picture p2, username u2 from account_details group by account_id)
                        as f on s.a_id2=f.a_id2
                    ";

    if (isset($_GET['order_by'])) {
        switch (intval($_GET['order_by'])){
            case 1:
                $base_query .= " ORDER BY date, ct desc";
                break;
            case 2:
                $base_query .= " ORDER BY ct, coalesce(md, date) desc";
                break;
            default:
                $base_query .= " ORDER BY coalesce(md, date), ct desc";
        }
    }

    // Prepare the statement:
    $stmt = $conn->prepare($base_query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
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

        <div class="section section-forum">
            <div class="container">
                <div class="main-body p-0">
                    <div class="inner-wrapper">
                        <div class="inner-main-header">
                            <form action='forum.php' method='get' id='form_forum_search'></form>
                            <select class="custom-select custom-select-sm w-auto mr-1" aria-label="order by" form='form_forum_search' name='order_by'>
                                <option value="0" selected>Latest activity</option>
                                <option value="1">Last posted</option>
                                <option value="2">Most replies</option>
                            </select>
                            <input type="text" class="form-control" placeholder="Search forum" name='query' form='form_forum_search'>
                            <button type='submit' class='btn-default' form='form_forum_search'>Go</button>
                        </div>
                            <?php if (isset($_SESSION['account_id'])) { ?>
                                <button class="fa-solid fa-plus" type="button" data-toggle="modal" data-target="#threadModal" id='forum_create_btn' title='create thread'></button>
                            <?php }?>
                        <div class='inner-main-body p-2 p-sm-3 collapse forum-content show'>
                        <?php
                            if ($result->num_rows == 0) {
                                echo "<h2 class='h2'>No threads available</h2>";
                            }
                            while ($row = $result->fetch_assoc()) {
                                if (isset($_GET['query'])) {
                                    $search_q = sanitize_input($_GET['query']);
                                    if (stripos($row['title'],$search_q)===false&&stripos($row['text'],$search_q)===false){
                                        continue;
                                    }
                                }
                                $last_activity = (empty($row['a_id2'])) ?
                                    "$row[u]<span class='text-secondary font-weight-bold'> posted this on $row[date]" :
                                    "$row[u2]<span class='text-secondary font-weight-bold'> replied on $row[md]";
                                $comments = (empty($row['ct'])) ? 0 : $row['ct'];
                                echo "
                                    <div class='card mb-2'>
                                        <div class='card-body p-2 p-sm-3'>
                                            <div class='media forum-item'>
                                                <div>
                                                    <a href='assets/acc_pic/$row[p]'><img src='assets/acc_pic/$row[p]' alt='User' class='rounded-circle'></a>
                                                    <span class='text-dark'>$row[u]</span>
                                                </div>
                                                    <div class='media-body'>
                                                    <a href='forum_sub.php?thread_id=$row[thread_id]' class='text-body h3'>$row[title]</a>
                                                    <p class='text-secondary'>
                                                        $row[text]
                                                    </p>
                                                    <p class='text-muted forum-date-posted'>$last_activity</span></p>
                                                </div>
                                                <div class='text-muted text-center align-self-center'>";
                                echo "
                                                    <span><i class='far fa-comment ml-2'></i> $comments</span>
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
                                <form action="forum.php" method="post">
                                    <div class="modal-header align-items-center bg-dark text-white">
                                        <span class="modal-title mb-0">New Discussion</span>
                                        <button type="button" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="btn-close btn-close-white">×</i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="threadTitle" class="text-dark">Title</label>
                                            <input id="threadTitle" type="text" class="form-control" placeholder="Enter title" maxlength='127' required name='create_thread_title'>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" name='create_thread_description' placeholder='Description' maxlength="255"></textarea>
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

</body>

</html>