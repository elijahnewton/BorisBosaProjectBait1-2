<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Student auth
if (!isset($_SESSION['student_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>