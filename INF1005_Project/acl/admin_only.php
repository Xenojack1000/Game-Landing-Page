<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['privilege']) or $_SESSION['privilege']!='admin') {
    header("Location: index.php");
} 
?>