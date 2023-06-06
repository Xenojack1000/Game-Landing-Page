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
    $stmt_carousel = $conn->prepare("SELECT * FROM carousel");
    $stmt_carousel->execute();
    $result_carousel = $stmt_carousel->get_result();
    $stmt_game = $conn->prepare("SELECT game_id, name FROM game order by name");
    $stmt_game->execute();
    $result_game = $stmt_game->get_result();
    $game_array = array();
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

?>


<h2 class='h2 section-title'>Carousel</h2>
<form method="POST" id="form_carousel_submit" enctype="multipart/form-data" action='admin.php#admin-carousel-table-end'></form>
<div class='table-responsive'>
    <table class='table table-borderless table-hover table-dark' id='admin_carousel_table'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Link</th>
                <th colspan="2">actions</th>
            </tr>
        </thead>

        <tbody style='max-height:100px'>
            <?php
            if ($success){
                $final = 0;
                while ($row = $result_carousel->fetch_assoc()) {
                    $game_id = $row['game_id'];
                    if ($game_id!=0) {
                        $game_name = $game_array[$game_id]['name'];
                        $carousel_link = "<a class='table_cell_action' 
                                            href=\"javascript:window.open('game.php?game_id=$game_id', 'newwindow', 'width=600,height=300')\"
                                        >
                                            $game_name
                                        </a>";
                    } else {
                        $carousel_link = "None";
                    }

                    echo "
                        <tr>
                            <td>$row[carousel_id]</td>
                            <td>
                                <a class='table_cell_action' 
                                href=\"javascript:window.open('assets/images/carousel/$row[image]', 'newwindow', 'width=600,height=200')\"
                                >
                                    $row[image]
                                </a>
                            </td>
                            <td>$carousel_link</td>
                            <td><a href='admin.php?edit_carousel=$row[carousel_id]#admin-carousel-table-end' class='table_cell_action'>Edit</a></td>
                            <td><button type='button' data-carousel_id='$row[carousel_id]' class='btn-warning w-100' onclick='confirmDeleteCarousel(this);'>Delete</button></td>
                        </tr>
                    ";
                    $final = $row['carousel_id'] + 1;
                    if (isset($_GET['edit_carousel'])) {
                        if ($row['carousel_id']== (int)$_GET['edit_carousel']){
                            $carousel_edit_row = $row;
                        }
                    }
                }
            }
            ?>
            <tr>
                <td colspan="5" style='background-color:#888'>
                    <span id="admin-carousel-table-end" style="position: absolute; transform: translateY(-50vh)"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo (isset($carousel_edit_row)) ? $carousel_edit_row['carousel_id']:'-'; ?>
                </td>
                <td>
                    <label for="carousel_image" class='table_cell_action'><?php echo isset($carousel_edit_row) ? 'Replace' : 'Select file';?></label>
                    <input type="file" id='carousel_image' name='carousel_image' accept=".png, .jpg, .jpeg" form="form_carousel_submit">
                </td>
                <td>
                    <select name="carousel_game_id" id="carousel_game_id" form="form_carousel_submit" class="form-select-lg mb-3" aria-label="carousel set link">
                        <option value="0"> -- do not link --</option>
                        <?php
                        foreach ($game_array as $game_id=>$game_details) {
                            if (isset($carousel_edit_row)){
                                echo ($carousel_edit_row['game_id']==$game_id) ? 
                                    "<option value='$game_id' selected>$game_details[name]</option>":
                                    "<option value='$game_id'>$game_details[name]</option>";
                            } else {
                                echo "<option value='$game_id'>$game_details[name]</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <a href='admin.php#admin-carousel-table-end' class='table_cell_action'<?php echo isset($carousel_edit_row) ? '': " style='display:none'"?>>Cancel</a>
                </td>
                <td>
                    <?php
                        echo (isset($carousel_edit_row)) ?
                        "<button class='btn-primary w-100' type='submit' id='carousel_submit' form='form_carousel_submit' name='carousel_edit' value='$carousel_edit_row[carousel_id]'>Update</button>" :
                        "<button class='btn-success w-100' type='submit' id='carousel_submit' form='form_carousel_submit' name='carousel_create' value='$final'>Add</button>";
                    ?>
                </td>
            </tr>

        </tbody>
    </table>
</div>

<script>
    $('#carousel_image').on('change',function(){
        var txt = this.value.split('\\').pop();
        if (txt=="") {
            txt="Select file";
        }
        $("label[for='carousel_image").html(txt);
    });

    function confirmDeleteCarousel(self) {

        document.getElementById("carousel_delete").carousel_delete_id.value = self.getAttribute("data-carousel_id");
        document.getElementById("carousel_delete_id").innerHTML = self.getAttribute("data-carousel_id");
        $("#carousel_delete_modal").modal("show");
    }
</script>

<div id="carousel_delete_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title text-dark">Delete Carousel Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body text-dark">
                <p>Are you sure you want to delete this carousel item?</p>
                <p id='carousel_delete_id'>Placeholder</p>
                <form method="POST" action="admin.php#admin-carousel-table-end" id="carousel_delete">
                    <input type="text" name="carousel_delete_id" class="d-none">
                </form>
            </div>

            <div class="modal-footer text-dark">
                <button type="button" class="btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" form="carousel_delete" class="btn-warning">Delete</button>
            </div>
        </div>
    </div>
</div>