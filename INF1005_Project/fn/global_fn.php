<?php

/*
    run js alert function with string param
*/
function scriptAlert($alert) {
    return "
        <script>
            alert('$alert');
        </script>
    ";
}

function sanitize_input($data) 
{    
    $data = trim($data);   
    $data = stripslashes($data);    
    $data = htmlspecialchars($data);   
    return $data; 
} 

function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

?>