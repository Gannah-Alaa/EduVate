<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$localhost = "localhost";
$username = "root";
$password = "";
$database ="sms";

$connect = mysqli_connect($localhost, $username ,$password , $database);
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

ob_start();

if(isset($_POST['logout'])){
    session_unset();
    session_destroy();
    header("location:login.php");
} 
?>