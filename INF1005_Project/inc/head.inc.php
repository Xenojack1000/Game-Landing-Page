<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
switch ($_SERVER['PHP_SELF']) {
    case "/INF1005_Project/aboutUs.php":
        $title = "About Us";
        break;
    case "/INF1005_Project/shopping.php":
        $title = "Shopping";
        break;
    case "/INF1005_Project/forum.php":
        $title = "Forum";
        break;
    case "/INF1005_Project/login.php":
        $title = "Login";
        break;
    case "/INF1005_Project/process_login.php":
        $title = "Login";
        break;
    case "/INF1005_Project/register.php":
        $title = "Join us now!";
        break;
    case "/INF1005_Project/process_register.php":
        $title = "Join us now!";
        break;
    case "/INF1005_Project/admin.php":
        $title = "Admin";
        break;
    case "/INF1005_Project/process_logout.php":
        $title = "Goodbye";
        break;
    case "/INF1005_Project/process_game_purchase.php";
        $title = "Thank you for your purchase";
        break;
    default:
        $title = "INF1005 - P4 G8";
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 
      - primary meta tags
    -->
    <title><?php echo $title ?></title>
    <meta name="title" content="Epic Games Made For True Gamers!">
    <meta name="description" content="INF1005 Project Game landing page">
    <!-- FontAwesomeFree -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!--     
      - favicon
    
    <link rel="shortcut icon" href="favicon.svg" type="image/svg+xml">-->
    <!--
      - Bootstrap CSS
    -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- 
      - google font link
    -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=Work+Sans:wght@600&display=swap" rel="stylesheet">
    <!-- 
      - custom css link
    -->
    <link rel="stylesheet" href="css/style.css">
    <!-- 
      - preload images
    -->
    <link rel="preload" as="image" href="assets/images/hero-banner.png">
    <link rel="preload" as="image" href="assets/images/hero-banner-bg.png">
    <!--
      - jQuery
    -->
    <script defer src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
    </script>

    <!--
      - Bootstrap JS
    -->
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous">
    </script>

    <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- 
      - custom js link
    -->
    <script defer src="js/script.js"></script>
</head>
<!--Footer
© 2023 GitHub, Inc.
Footer navigation
Terms
Privacy
Security
Status-->