<?php
require_once '../config/db.php';
require_once '../includes/auth.php'; // ensures student/admin logged in
if (!isset($_SESSION['student_id'])) { header("Location: ../login.php"); exit; }
include '../includes/header.php';
$student_id = $_SESSION['student_id'];
$result = mysqli_query($conn, "SELECT * FROM students WHERE id = $student_id");
$student = mysqli_fetch_assoc($result);
?>
<h2>Welcome, <?= $student['full_name'] ?></h2>
<div class="row mt-4">
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5>My Bookings</h5>
        <?php
        $bcount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bookings WHERE student_id=$student_id"));
        ?>
        <p class="display-6"><?= $bcount['cnt'] ?></p>
        <a href="my_bookings.php" class="btn btn-sm btn-primary">View</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5>Available Hostels</h5>
        <?php
        $hostel_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM hostels"));
        ?>
        <p class="display-6"><?= $hostel_count['cnt'] ?></p>
        <a href="browse_hostels.php" class="btn btn-sm btn-success">Browse</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-body">
        <h5>Submit Complaint</h5>
        <p>Having issues?</p>
        <a href="submit_complaint.php" class="btn btn-sm btn-warning">Complain</a>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>