<?php
function isLoggedIn() {
    return isset($_SESSION['student_id']) || isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>