<?php

//process session info to load correct page
if (isset($_SESSION['account_id'])) {
    //0 = href, 1 = class, 2 = innerhtml(text)
    $btn1 = array("profile.php", "btn", "PROFILE");
    $btn2 = array("process_logout.php", "btn", "LOGOUT");
} else {
    $btn1 = array("register.php", "btn", "REGISTER");
    $btn2 = array("login.php", "btn", "LOGIN");
}
?>

<header class="header active" data-header>
    <div class="topnav" id="myTopnav">

        <a href="index.php" class="logo">
            <img src="assets/images/logo.png" alt="team 8">
        </a>
            <ul class="navbar-list">
                <li class="navbar-item">
                    <a href="index.php">home</a>
                </li>

                <li class="navbar-item">
                    <a href="aboutUs.php">about</a>
                </li>

                <li class="navbar-item">
                    <a href="shopping.php">shopping</a>
                </li>

                <li class="navbar-item">
                    <a href="forum.php">forum</a>
                </li>
                <?php if (isset($_SESSION['privilege'])&&$_SESSION['privilege']=='admin'){?>
                <li class="navbar-item">
                    <a href="admin.php">admin</a>
                </li>
                <?php }?>
            </ul>

            <ul class="navbar-list">
                <?php
                foreach (array($btn1, $btn2) as &$data) {
                    echo "
                <li class='navbar-item'>
                <a href='$data[0]' class='$data[1]' data-btn>$data[2]</a>
                </li>
                ";
                }
                ?>

            </ul>

            <ul class="navbar-list" id="navbar-list-cart">
                <li class="navbar-item" id="navbar-item-cart">
                    <a href="shopping.php#shoppingcart" class="p-1" title='your cart'>
                        <i class="fas fa-cart-arrow-down fa-2x"></i>
                    </a>
                </li>
                <li>
                </li>
            </ul>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()" aria-label="cart">
                <i class="fa fa-bars"></i>
            </a>
    </div>
</header>