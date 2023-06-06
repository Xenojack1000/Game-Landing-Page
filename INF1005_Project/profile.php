<?php include "acl/loggedin_only.php"; ?>
<!DOCTYPE html>

<html lang="en">
    <?php
    include "inc/head.inc.php";
    require "fn/u_fn.php";
    
    $user = $name = $email = $number = $last_login = $picture = $errorMsg = "";
    $success = $news = $promo = true;

    if (isset($_FILES["image"]["name"])){
        $imgName = $_FILES["image"]["name"];
        $imgSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $validImgExt = ['jpg', 'jpeg', 'png'];
        $imgExt = explode('.', $imgName);
        $imgExt = strtolower(end($imgExt));
        if (!in_array($imgExt, $validImgExt)){
            echo scriptAlert("Invalid file extension, please upload a .jpg, .jpeg or .png file.");
        } elseif ($imgSize > 10485760) {
            echo scriptAlert("File size is too big, maximum allowed 10MB.");
        } else {
            // $newImgName = $_SESSION['account_id'] . "_" . date("Y.m.d") . "-" . date("h.i.sa") . "." . $imgExt;
            $newImgName = $_SESSION['account_id'] . "." . $imgExt;
            updateProfilePicture();
            if ($success) {
                move_uploaded_file($tmpName, "assets/acc_pic/" . $newImgName);
            } else {
                scriptAlert($errorMsg);
            }
        }
    }

    getUserDetails();

    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $valid_acc_id = $_SESSION['account_id'];
        //connect to db
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

        $stmt_purchases = $conn->prepare("select ph.*, g.name
                                        from purchase_history ph
                                        LEFT OUTER JOIN (SELECT game_id, name FROM game)
                                        AS g ON ph.game_id=g.game_id
                                        where account_id=?
                                        ");
        $stmt_purchases->bind_param("i", $valid_acc_id);
        $stmt_purchases->execute();
        $result_purchases = $stmt_purchases->get_result();
        $stmt_purchases->close();
        $conn->close();
        if ($result_purchases->num_rows==0) {
            $my_purchases_html = "
                                    <h3 class='h3 section-title'>You have no purchases</h3>
                                    <p>Visit the <a href='shopping.php'>store?</a></p>
            ";

        } else {
            $my_purchases_html = "
                                    <table class='table table-hover table-borderless text-left table-dark table-responsive'>
                                        <thead>
                                            <tr>
                                                <th>Game</th>
                                                <th>Purchase date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                ";
            while ($row=$result_purchases->fetch_assoc()) {
                $my_purchases_html.="
                                    <tr>
                                        <td><a href='game.php?game_id=$row[game_id]' class='table_cell_action'>$row[name]</a></td>
                                        <td>$row[date_purchase]</td>
                                    </tr>
                                    ";
            }

            $my_purchases_html .= "
                                        </tbody>
                                    </table>
                                ";
        }
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
        $success = false;
    }
    ?>

    <body>
        <?php
        include "inc/nav.inc.php";
        ?>
        <main class='container'>
            <section id='profile'>
                <h1 class='h1 section-title'>Profile</h1>
                <form action="profile.php" method="post" id="pfp_form" enctype="multipart/form-data">
                    <div class="upload">
                        <img src="assets/acc_pic/<?php echo $picture;?>" alt="profile picture" class="rounded-circle shadow-4-strong mx-auto d-block">
                        <div>
                            <i class="fa fa-camera"></i>
                            <label for="pfp_input">UPLOAD</label>
                            <input type="file" name="image" id="pfp_input" accept=".jpg, .jpeg, .png">
                        </div>
                    </div>
                </form>
                <h2 class='h2 section-header'>Account Details</h2>
                <?php
                echo"
                    <table class='table table-hover table-borderless text-left table-dark table-responsive'>
                        <tbody>
                            <tr>
                                <td>Username:</td>
                                <td>$user</td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td>$name</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>$email</td>
                            </tr>
                            <tr>
                                <td>Number:</td>
                                <td>$number</td>
                            </tr>
                            <tr>
                                <td>Last logged in:</td>
                                <td>$last_login</td>
                            </tr>
                            <tr>
                                <td>Send me newsletter:</td>
                                <td>$news</td>
                            </tr>
                            <tr>
                                <td>Send me promotions and discount codes:</td>
                                <td>$promo</td>
                            </tr>
                        </tbody>
                    </table>
                ";
                ?>
            </section>
            <section id='my-purchases'>
                <h2 class='h2 section-title'>My purchases</h2>
                <?php echo "$my_purchases_html"; ?>
            </section>
        </main>
        <?php
        include "inc/footer.inc.php";
        ?>
        <script defer src="js/profile_page.js"></script>
        <script>
            $(document).ready(function() {
                setPageTitle("Profile - <?php echo $_SESSION['name']?>");
            });
        </script>
    </body>
</html>