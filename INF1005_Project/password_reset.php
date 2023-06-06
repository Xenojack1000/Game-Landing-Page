<?php
include "inc/head.inc.php";
?>
<html>
    <head>
        <title>Login</title> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
              integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="css/main.css">
        <!--jQuery-->
        <script defer
                src="https://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous">
        </script>

        <!--Bootstrap JS-->
        <script defer
                src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
                integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm"
                crossorigin="anonymous">
        </script>
        <script defer src="js/main.js"></script>
    </head>
    <body>
        <main class="container">
            <h1>Password Reset</h1>
           <form action="process_pw_reset.php" method="post">
                <div class="form-group">
                    <label for="reset">Enter email to send password reset link to :</label>
                    <input class="form-control" id="reset" 
                            type="email" maxlength="45" name="email" placeholder="Email" required>
                </div>
            
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Submit</button>

                </div>
            </form>

        </main>
        <?php
        include "inc/footer.inc.php";
        ?>
    </body>
</html>
