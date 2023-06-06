<?php

$default = array();

foreach(array('user','name','email','number') as &$value) {
    if (isset($_POST[$value])){
        $default[] = $_POST[$value];
    } else {
        $default[] = '';
    }
}
if (isset($_POST['user'])) {
    
}

echo "
    <form action='process_register.php' method='post' id='userForm'>
        <div class='form-group'>
            <label for='user'>Username:</label>
            <input class='input-field' type='text' id='user' name='user' placeholder='Enter desired username*' maxlength='20' required value='$default[0]'>
        </div>

        <div class='form-group'>
            <label for='name'>Name:</label>
            <input class='input-field' type='text' id='name' name='name' placeholder='Enter your name*' required value='$default[1]'>
        </div>

        <div class='form-group'>
            <label for='email'>Email:</label>
            <input class='input-field' type='email' id='email' name='email' placeholder='Enter email*' required value='$default[2]'>
        </div>
        <div class='form-group'>
            <label for='number'>Number:</label>
            <input class='input-field' type='text' id='number' name='number' placeholder='Enter number' maxlength='8' minlength='8' required value='$default[3]'>
        </div>

        <div class='form-group'>
            <label for='pwd'>Password:</label>
            <input class='input-field' type='password' id='pwd' name='pwd' placeholder='Enter password*' maxlength='45' required>
        </div>

        <div class='form-group'>
            <label for='pwd_confirm'>Confirm password:</label>
            <input class='input-field' type='password' id='pwd_confirm' name='pwd_confirm' placeholder='Confirm password*' maxlength='45' required>
        </div>

        

        <div class='form-check'>
            <label>
                <input type='checkbox' name='agree' required>
                Agree to <a href='Terms.html' target='_blank' rel='noopener noreferrer' class='link_underline'>Terms and Conditions</a>*
            </label>
        </div>
        <div class='form-check'>
            <label>
                <input type='checkbox' name='news' checked>
                Subscribe to newsletter
            </label>
        </div>
        <div class='form-check'>
            <label>
                <input type='checkbox' name='promo' checked>
                Send me promo codes and discounts
            </label>
        </div>

        <div class='form-group'>
            <button class='btn btn-primary' type='submit'>REGISTER</button>
        </div>

        <div class='form-group'>
            <p id='form-warning'>* required fields</p>
        </div>
    </form>
";
?>