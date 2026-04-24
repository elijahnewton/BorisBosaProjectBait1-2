<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
if (!isset($_GET['room_id'])) { header("Location: browse_hostels.php"); exit; }
$room_id = (int)$_GET['room_id'];
$room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rooms WHERE id=$room_id"));
if (!$room || $room['status']=='Maintenance' || ($room['capacity'] - $room['current_occupancy']) <=0) {
    echo "Room not available."; exit;
}
include '../includes/header.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $checkin = $_POST['expected_checkin'];
    $student_id = $_SESSION['student_id'];
    $booking_date = date('Y-m-d');
    $stmt = mysqli_prepare($conn, "INSERT INTO bookings (student_id, room_id, booking_date, expected_checkin, status) VALUES (?, ?, ?, ?, 'Pending')");
    mysqli_stmt_bind_param($stmt, 'iiss', $student_id, $room_id, $booking_date, $checkin);
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success'>Booking request sent! Wait for admin approval.</div>";
    } else {
        $error = "Booking failed. Try again.";
    }
}
?>
<h3>Book Room: <?= $room['room_number'] ?> (<?= $room['room_type'] ?>)</h3>
<p>Price: Rs. <?= $room['price_per_month'] ?> per month</p>
<form method="post">
  <div class="mb-3">
    <label>Expected Check-in Date</label>
    <input type="date" name="expected_checkin" class="form-control" min="<?= date('Y-m-d') ?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit Booking Request</button>
  <a href="room_details.php?hostel_id=<?= $room['hostel_id'] ?>" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../includes/footer.php'; ?>