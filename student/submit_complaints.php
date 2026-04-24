<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $booking_id = !empty($_POST['booking_id']) ? (int)$_POST['booking_id'] : NULL;
    $student_id = $_SESSION['student_id'];
    $stmt = mysqli_prepare($conn, "INSERT INTO complaints (student_id, booking_id, subject, description) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'iiss', $student_id, $booking_id, $subject, $desc);
    mysqli_stmt_execute($stmt);
    echo "<div class='alert alert-success'>Complaint submitted.</div>";
}
?>
<h2>Submit Complaint</h2>
<form method="post">
  <div class="mb-3">
    <label>Subject</label>
    <input type="text" name="subject" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control" rows="4" required></textarea>
  </div>
  <div class="mb-3">
    <label>Related Booking (optional)</label>
    <select name="booking_id" class="form-control">
      <option value="">-- None --</option>
      <?php
      $result = mysqli_query($conn, "SELECT id, room_id FROM bookings WHERE student_id={$_SESSION['student_id']} AND status='Approved'");
      while($b = mysqli_fetch_assoc($result)) {
          echo "<option value='{$b['id']}'>Booking #{$b['id']} (Room {$b['room_id']})</option>";
      }
      ?>
    </select>
  </div>
  <button type="submit" class="btn btn-warning">Submit</button>
</form>
<?php include '../includes/footer.php'; ?>