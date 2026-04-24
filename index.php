<?php
require_once 'config/db.php';
if (isset($_SESSION['student_id'])) {
    header("Location: student/dashboard.php");
    exit;
} elseif (isset($_SESSION['admin_id'])) {
    header("Location: admin/dashboard.php");
    exit;
}
include 'includes/header.php';
?>
<div class="jumbotron text-center mt-5">
  <h1>Welcome to University Hostel Booking</h1>
  <p>Find your perfect room on campus.</p>
  <a href="register.php" class="btn btn-primary btn-lg">Register Now</a>
  <a href="login.php" class="btn btn-outline-secondary btn-lg">Login</a>
</div>
<?php include 'includes/footer.php'; ?>