<?php

require "fn/db_loc.php";
require "fn/global_fn.php";

/*
    GAME
*/
//create game or update game game_create game_edit
if (isset($_POST['game_create']) || isset($_POST['game_edit'])) {
    global $db_file;
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //validation
    $game_name = sanitize_input($_POST['game_name']);
    $game_price = floatval($_POST['game_price']);
    $game_description = sanitize_input($_POST['game_description']);
    $game_gameplay = sanitize_input($_POST['game_gameplay']);
    $game_youtube = sanitize_input($_POST['game_youtube']);
    if (validateDate($_POST['game_date'])) {
        $game_date = $_POST['game_date'];
    } else {
        $_SESSION['msg'] = "Date format incorrect. Required: DD/MM/YYYY, received: " . $_POST['game_date'];
    }
    if (isset($_POST['game_create'])){
        $newGifName = "default.gif";
        $newVideoName = "default.mp4";
        $newImgName = "default.jpg";
    }
    $case = 1;
    $game_id = (isset($_POST['game_create'])) ? intval($_POST['game_create']) : intval($_POST['game_edit']);
    if ($_FILES["game_thumbnail"]["size"]!=0) {
        $gifName = $_FILES["game_thumbnail"]["name"];
        $gifSize = $_FILES["game_thumbnail"]["size"];
        $tmpGifName = $_FILES["game_thumbnail"]["tmp_name"];
        $validGifExt = ['gif'];
        $gifExt = explode('.', $gifName);
        $gifExt = strtolower(end($gifExt));
        if (!in_array($gifExt, $validGifExt)){
            $_SESSION['msg'] = "Invalid file extension, please upload a .jpg, .jpeg, .png or .gif file.";
        } elseif ($gifSize > 10485760) {
            $_SESSION['msg'] = "File size is too big, maximum allowed 10MB.";
        } else {
            $newGifName = $game_id . "." . $gifExt;
            move_uploaded_file($tmpGifName, "assets/game/landing/" . $newGifName);
        }
        $case *= 2;
    }
    if ($_FILES["game_video"]["size"]!=0) {
        $videoName = $_FILES["game_video"]["name"];
        $videoSize = $_FILES["game_video"]["size"];
        $tmpVideoName = $_FILES["game_video"]["tmp_name"];
        $validVideoExt = ['mp4'];
        $videoExt = explode('.', $videoName);
        $videoExt = strtolower(end($videoExt));
        if (!in_array($videoExt, $validVideoExt)){
            $_SESSION['msg'] = "Invalid file extension, please upload a .mp4 file.";
        } elseif ($videoSize > 52428800) {
            $_SESSION['msg'] = "File size is too big, maximum allowed 50MB.";
        } else {
            $newVideoName = $game_id . "." . $videoExt;
            move_uploaded_file($tmpVideoName, "assets/game/video/" . $newVideoName);
        }
        $case *= 3;
    }
    if ($_FILES["game_shop_thumbnail"]["size"]!=0) {
        $imgName = $_FILES["game_shop_thumbnail"]["name"];
        $imgSize = $_FILES["game_shop_thumbnail"]["size"];
        $tmpImgName = $_FILES["game_shop_thumbnail"]["tmp_name"];
        $validImgExt = ['jpg', 'jpeg', 'png'];
        $imgExt = explode('.', $imgName);
        $imgExt = strtolower(end($imgExt));
        if (!in_array($imgExt, $validImgExt)){
            $_SESSION['msg'] = "Invalid file extension, please upload a .jpg, .jpeg or .png file.";
        } elseif ($imgSize > 10485760) {
            $_SESSION['msg'] = "File size is too big, maximum allowed 10MB.";
        } else {
            $newImgName = $game_id . "." . $imgExt;
            move_uploaded_file($tmpImgName, "assets/game/shop/" . $newImgName);
        }
        $case *= 4;
    }

    if (!isset($_SESSION['msg'])) {
        try {
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if (isset($_POST['game_create'])) {
                // Create new game
                $stmt = $conn->prepare("INSERT INTO game (name, price, description, release_date, thumbnail, video, gameplay, shop_thumbnail, youtube) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                // Bind & execute the query statement:
                $stmt->bind_param("sdsssssss", $game_name, $game_price, $game_description, $game_date, $newGifName, $newVideoName, $game_gameplay, $newImgName, $game_youtube);
                $_SESSION['msg'] = "Game successfully created: " . $game_name;
            } else {
                // Edit game
                switch ($case) {
                    case 1: // !(gif || vid || img)
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, gameplay=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdssssi", $game_name, $game_price, $game_description, $game_date, $game_gameplay, $game_youtube, $game_id);
                        break;
                    case 2: // gif only
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, thumbnail=?, gameplay=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdsssssi", $game_name, $game_price, $game_description, $game_date, $newGifName, $game_gameplay, $game_youtube, $game_id);
                        break;
                    case 3: // vid only
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, video=?, gameplay=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdsssssi", $game_name, $game_price, $game_description, $game_date, $newVideoName, $game_gameplay, $game_youtube, $game_id);
                        break;
                    case 4: // img only
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, gameplay=?, shop_thumbnail=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdsssssi", $game_name, $game_price, $game_description, $game_date, $game_gameplay, $newImgName, $game_youtube, $game_id);
                        break;
                    case 6: // gif AND video
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, thumbnail=?, video=?, gameplay=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdssssssi", $game_name, $game_price, $game_description, $game_date, $newGifName, $newVideoName, $game_gameplay, $game_youtube, $game_id);
                        break;
                    case 8: // gif AND img
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, thumbnail=?, gameplay=?, shop_thumbnail=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdssssssi", $game_name, $game_price, $game_description, $game_date, $newGifName, $game_gameplay, $newImgName, $game_youtube, $game_id);
                        break;
                    case 12: // vid AND img
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, video=?, gameplay=?, shop_thumbnail=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdssssssi", $game_name, $game_price, $game_description, $game_date, $newVideoName, $game_gameplay, $newImgName, $game_youtube, $game_id);
                        break;
                    case 24: // gif AND video AND img
                        $stmt = $conn->prepare("UPDATE game SET name=?, price=?, description=?, release_date=?, thumbnail=?, video=?, gameplay=?, shop_thumbnail=?, youtube=? where game_id=?");
                        $stmt->bind_param("sdsssssssi", $game_name, $game_price, $game_description, $game_date, $newGifName, $newVideoName, $game_gameplay, $newImgName, $game_youtube, $game_id);
                        break;
                }
                $_SESSION['msg'] = "Update to game " . $game_id . " successful.";
            }
            $stmt->execute();
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $_SESSION['msg'] = $e->getMessage();
        }
    }
} //delete game game_delete
else if (isset($_POST['game_delete_id'])) {
    global $db_file;
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $game_delete_id = (int)$_POST['game_delete_id'];
    try {
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn->prepare("DELETE FROM game WHERE game_id=?");
        $stmt->bind_param("i", $game_delete_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        $_SESSION['msg'] = "Successfully deleted game #" . $game_delete_id . ".";
    } catch (Exception $e) {
        $_SESSION['msg'] = $e->getMessage();
    }
}

/*
    CAROUSEL
*/

//create carousel or update carousel carousel_create carousel_edit
if (isset($_POST['carousel_create']) || isset($_POST['carousel_edit'])) {
    global $db_file;
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //validation
    if (isset($_POST['carousel_create'])){
        $newImgName = "default.jpg";
    }
    $carousel_id = (isset($_POST['carousel_create'])) ? intval($_POST['carousel_create']) : intval($_POST['carousel_edit']);
    if ($_FILES["carousel_image"]["size"]!=0) {
        $imgName = $_FILES["carousel_image"]["name"];
        $imgSize = $_FILES["carousel_image"]["size"];
        $tmpImgName = $_FILES["carousel_image"]["tmp_name"];
        $validImgExt = ['jpg', 'jpeg', 'png'];
        $imgExt = explode('.', $imgName);
        $imgExt = strtolower(end($imgExt));
        if (!in_array($imgExt, $validImgExt)){
            $_SESSION['msg'] = "Invalid file extension, please upload a .jpg, .jpeg or .png file.";
        } elseif ($imgSize > 10485760) {
            $_SESSION['msg'] = "File size is too big, maximum allowed 10MB.";
        } else {
            $newImgName = $carousel_id . "." . $imgExt;
            move_uploaded_file($tmpImgName, "assets/images/carousel/" . $newImgName);
        }
    }
    if (!isset($_SESSION['msg'])) {
        $carousel_link = intval($_POST['carousel_game_id']);
        try {
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            if (isset($_POST['carousel_create'])) {
                // Create new carousel
                $stmt = $conn->prepare("INSERT INTO carousel (image, game_id) VALUES (?, ?)");
                // Bind & execute the query statement:
                $stmt->bind_param("si", $newImgName, $carousel_link);
                $_SESSION['msg'] = "Carousel successfully created.";
            } else {
                // Edit carousel
                if (isset($newImgName)) {
                        $stmt = $conn->prepare("UPDATE carousel SET image=?, game_id=? where carousel_id=?");
                        $stmt->bind_param("sii", $newImgName, $carousel_link, $carousel_id);
                } else {
                    $stmt = $conn->prepare("UPDATE carousel SET game_id=? where carousel_id=?");
                    $stmt->bind_param("ii", $carousel_link, $carousel_id);
                }
                $_SESSION['msg'] = "Update to carousel " . $carousel_id . " successful.";
            }
            $stmt->execute();
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $_SESSION['msg'] = $e->getMessage();
        }
    }
} //delete game game_delete
else if (isset($_POST['carousel_delete_id'])) {
    global $db_file;
    $config = parse_ini_file($db_file);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $carousel_delete_id = (int)$_POST['carousel_delete_id'];
    try {
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        $stmt = $conn->prepare("DELETE FROM carousel WHERE carousel_id=?");
        $stmt->bind_param("i", $carousel_delete_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        $_SESSION['msg'] = "Successfully deleted carousel #" . $carousel_delete_id . ".";
    } catch (Exception $e) {
        $_SESSION['msg'] = $e->getMessage();
    }
}

if (isset($_SESSION['msg'])) {
    echo "
        <h2 class='h2'>$_SESSION[msg]</h2>
    ";
    unset($_SESSION['msg']);
}