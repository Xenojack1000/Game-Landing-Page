<?php

$default = array();

foreach(array('user') as &$value) {
    if (isset($_POST[$value])){
        $default[] = $_POST[$value];
    } else {
        $default[] = '';
    }
}
if (isset($_POST['user'])) {
    
}

echo "
    <form action='process_login.php' method='post' id='userForm'>
        <div class='form-group'>
            <label for='username'>Username or Email:</label>
            <input class='input-field' id='username' maxlength='45' name='user' placeholder='Enter username or email' required value='$default[0]'>
        </div>
        <div class='form-group'>
            <label for='password'>Password:</label>
            <input class='input-field' type='password' id='password' required maxlength='45' name='password' placeholder='Enter password'>
        </div>
        <div class='form-group'>
            <button class='btn btn-primary' type='submit' id='login_submit'>Login</button>

        </div>
    </form>
    <a class='btn btn-primary' href='password_reset.php' >Forgot password?</a>
";
?>