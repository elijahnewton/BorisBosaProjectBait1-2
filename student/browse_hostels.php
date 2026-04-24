<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
$gender = $_SESSION['student_gender'] ?? ''; // we need to store gender in session. Let's fetch.
$student_id = $_SESSION['student_id'];
$stu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gender FROM students WHERE id=$student_id"));
$gender = $stu['gender'];
$_SESSION['student_gender'] = $gender;

$hostel_type = ($gender == 'Male') ? 'Boys' : (($gender == 'Female') ? 'Girls' : 'Mixed');
// allow mixed for all
$sql = "SELECT h.*, (SELECT COUNT(*) FROM rooms WHERE hostel_id = h.id AND (current_occupancy < capacity) AND status != 'Maintenance') AS available_rooms FROM hostels h WHERE h.type IN ('$hostel_type', 'Mixed')";
$hostels = mysqli_query($conn, $sql);
?>
<h2>Available Hostels</h2>
<div class="row">
<?php while($hostel = mysqli_fetch_assoc($hostels)): ?>
  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title"><?= $hostel['name'] ?></h5>
        <p class="card-text"><?= $hostel['description'] ?></p>
        <p>Type: <?= $hostel['type'] ?> | Address: <?= $hostel['address'] ?></p>
        <a href="room_details.php?hostel_id=<?= $hostel['id'] ?>" class="btn btn-primary">View Rooms (<?= $hostel['available_rooms'] ?> available)</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
<?php include '../includes/footer.php'; ?>