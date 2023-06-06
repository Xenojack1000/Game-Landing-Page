<!DOCTYPE html>

<html lang="en">
    <main style="white-space:pre-line">
        <?php
        require "../fn/db_loc.php";
        // Create database connection.
        global $db_file;
        $config = parse_ini_file('../' . $db_file);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            //connect to db
            $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
            // Prepare the statement:
            $stmt = $conn->prepare("SELECT description FROM game where game_id=?");
            $stmt->bind_param("i", $_GET['game_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
        echo "
            $row[description]
        ";
        ?>
    </main>
</html>