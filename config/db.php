<?php
$conn = mysqli_connect("localhost","root","","air_ticket");
if(!$conn){
    die("Database connection failed");
}
session_start();
?>
