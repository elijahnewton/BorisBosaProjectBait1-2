<?php
session_start();
$host = 'localhost';
$user = 'boris';
$pass = '12345678';
$db   = 'hostel_booking';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
