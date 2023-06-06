<?php

require "fn/db_loc.php";

// Create database connection.
$config = parse_ini_file($db_file);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$success = true;
try {
    //connect to db
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    // Prepare the statement:
    $stmt = $conn->prepare("SELECT * FROM game");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $success = false;
}

?>


<h2 class='h2 section-title'>Games</h2>
<form method="POST" id="form_game_submit" enctype="multipart/form-data" action='admin.php#admin-game-table-end'></form>
<div class='table-responsive'>
    <table class='table table-borderless table-hover table-dark' id='admin_game_table'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Overview</th>
                <th>Gameplay</th>
                <th>YT</th>
                <th>Release Date</th>
                <th>GIF</th>
                <th>Img</th>
                <th>Vid</th>
                <th colspan="2">actions</th>
            </tr>
        </thead>

        <tbody style='max-height:100px'>
            <?php
            if ($success){
                $final = 0;
                while ($row = $result->fetch_assoc()) {
                    echo "
                        <tr>
                            <td>$row[game_id]</td>
                            <td>
                                <a class='table_cell_action' 
                                href=\"javascript:window.open('game.php?game_id=$row[game_id]', 'newwindow', 'width=600,height=300')\"
                                >
                                    $row[name]
                                </a>
                            </td>
                            <td>$row[price]</td>
                            <td>
                                <a class='table_cell_action' 
                                    href=\"javascript:window.open('admin/game_overview.php?game_id=$row[game_id]', 'newwindow', 'width=600,height=300')\"
                                >
                                    Overview
                                </a>
                            </td>
                            <td>
                                <a class='table_cell_action' 
                                    href=\"javascript:window.open('admin/game_gameplay.php?game_id=$row[game_id]', 'newwindow', 'width=600,height=300')\"
                                >
                                    Gameplay
                                </a>
                            </td>
                            <td>
                                <a class='table_cell_action' 
                                    href=\"javascript:window.open('https://www.youtube.com/embed/$row[youtube]?autoplay=1', 'newwindow', 'width=500,height=500')\"
                                >
                                    uTube
                                </a>
                            </td>
                            <td>$row[release_date]</td>
                            <td>
                                <a class='table_cell_action' 
                                href=\"javascript:window.open('assets/game/landing/$row[thumbnail]', 'newwindow', 'width=533,height=300')\"
                                >
                                    $row[thumbnail]
                                </a>
                            </td>
                            <td>
                                <a class='table_cell_action' 
                                href=\"javascript:window.open('assets/game/shop/$row[shop_thumbnail]', 'newwindow', 'width=255,height=255')\"
                                >
                                    $row[shop_thumbnail]
                                </a>
                            </td>
                            <td>
                                <a class='table_cell_action' 
                                href=\"javascript:window.open('assets/game/video/$row[video]', 'newwindow', 'width=600,height=360')\"
                                >
                                    $row[video]
                                </a>
                            </td>
                            <td><a href='admin.php?edit_game=$row[game_id]#admin-game-table-end' class='table_cell_action'>Edit</a></td>
                            <td><button type='button' data-game_id='$row[game_id]' data-game_name='$row[name]' class='btn-warning w-100' onclick='confirmDeleteGame(this);'>Delete</button></td>
                        </tr>
                    ";
                    $final = $row['game_id'] + 1;
                    if (isset($_GET['edit_game'])) {
                        if ($row['game_id']== (int)$_GET['edit_game']){
                            $game_edit_row = $row;
                        }
                    }
                }
            }
            ?>
            <tr>
                <td colspan="12" style='background-color:#888'>
                    <span id="admin-game-table-end" style="position: absolute; transform: translateY(-50vh)"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo (isset($game_edit_row)) ? $game_edit_row['game_id']:'-'; ?>
                </td>
                <td>
                    <input id='game_name' maxlength='45' name='game_name' placeholder='Name' required form="form_game_submit"<?php echo (isset($game_edit_row)) ? " value='$game_edit_row[name]'":""; ?>>
                </td>
                <td>
                    <input type='number' id='game_price' max='99999999.99' step='0.01' name='game_price' placeholder='Price' required form="form_game_submit"<?php echo (isset($game_edit_row)) ? " value='$game_edit_row[price]'":""; ?>>
                </td>
                <td>
                    <textarea id='game_description' maxlength='1023' name='game_description' placeholder='Overview' required form="form_game_submit"><?php echo (isset($game_edit_row)) ? " $game_edit_row[description]":""; ?></textarea>
                </td>
                <td>
                    <textarea id='game_gameplay' maxlength='1500' name='game_gameplay' placeholder='Gameplay' required form="form_game_submit"><?php echo (isset($game_edit_row)) ? " $game_edit_row[gameplay]":""; ?></textarea>
                </td>
                <td>
                    <input id='game_youtube' maxlength='60' name='game_youtube' placeholder='uTube' required form="form_game_submit"<?php echo (isset($game_edit_row)) ? " value='$game_edit_row[youtube]'":""; ?>>
                </td>
                <td>
                    <input type="date" id='game_date' name='game_date' required form="form_game_submit" value='<?php echo (isset($game_edit_row)) ? $game_edit_row['release_date'] : date('Y-m-d')?>' aria-label='game release date'>
                </td>
                <td>
                    <label for="game_thumbnail" class='table_cell_action'><?php echo isset($game_edit_row) ? 'Replace' : 'Select file';?></label>
                    <input type="file" id='game_thumbnail' name='game_thumbnail' accept=".gif" form="form_game_submit">
                </td>
                <td>
                    <label for="game_shop_thumbnail" class='table_cell_action'><?php echo isset($game_edit_row) ? 'Replace' : 'Select file';?></label>
                    <input type="file" id='game_shop_thumbnail' name='game_shop_thumbnail' accept=".png, .jpg, .jpeg" form="form_game_submit">
                </td>
                <td>
                    <label for="game_video" class='table_cell_action'><?php echo isset($game_edit_row) ? 'Replace' : 'Select file';?></label>
                    <input type="file" id='game_video' name='game_video' accept=".mp4" form="form_game_submit">
                </td>
                <td>
                    <a href='admin.php#admin-game-table-end' class='table_cell_action'<?php echo isset($game_edit_row) ? '': " style='display:none'"?>>Cancel</a>
                </td>
                <td>
                    <?php
                        echo (isset($game_edit_row)) ?
                        "<button class='btn-primary w-100' type='submit' id='game_submit' form='form_game_submit' name='game_edit' value='$game_edit_row[game_id]'>Update</button>" :
                        "<button class='btn-success w-100' type='submit' id='game_submit' form='form_game_submit' name='game_create' value='$final'>Add</button>";
                    ?>
                </td>
            </tr>

        </tbody>
    </table>
</div>

<script>
    $('#game_thumbnail').on('change',function(){
        var txt = this.value.split('\\').pop();
        if (txt=="") {
            txt="Select file";
        }
        $("label[for='game_thumbnail").html(txt);
    });
    $('#game_shop_thumbnail').on('change',function(){
        var txt = this.value.split('\\').pop();
        if (txt=="") {
            txt="Select file";
        }
        $("label[for='game_shop_thumbnail").html(txt);
    });
    $('#game_video').on('change',function(){
        var txt = this.value.split('\\').pop();
        if (txt=="") {
            txt="Select file";
        }
        $("label[for='game_video").html(txt);
    });

    function confirmDeleteGame(self) {

        document.getElementById("game_delete").game_delete_id.value = self.getAttribute("data-game_id");
        document.getElementById("game_delete_name").innerHTML = self.getAttribute("data-game_name");
        $("#game_delete_modal").modal("show");
    }
</script>

<div id="game_delete_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title text-dark">Delete Game</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body text-dark">
                <p>Are you sure you want to delete this game?</p>
                <p id='game_delete_name'>Placeholder</p>
                <form method="POST" action="admin.php#admin-game-table-end" id="game_delete">
                    <input type="text" name="game_delete_id" class="d-none">
                </form>
            </div>

            <div class="modal-footer text-dark">
                <button type="button" class="btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" form="game_delete" class="btn-warning">Delete</button>
            </div>
        </div>
    </div>
</div>