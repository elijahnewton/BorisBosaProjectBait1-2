<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit; }
include '../includes/header.php';

// Stats
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM students"))['cnt'];
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM rooms"))['cnt'];
$occupied = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(current_occupancy) as occ FROM rooms"))['occ'];
$capacity = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(capacity) as cap FROM rooms"))['cap'];
$occ_rate = $capacity > 0 ? round(($occupied/$capacity)*100, 1) : 0;
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(r.price_per_month) as total FROM bookings b JOIN rooms r ON b.room_id=r.id WHERE b.status='Approved' AND b.payment_status='Paid' AND MONTH(b.booking_date)=MONTH(CURDATE())"))['total'];
$revenue = $revenue ?: 0;
?>
<h2>Admin Dashboard</h2>
<div class="row mt-3">
  <div class="col-md-3">
    <div class="card text-white bg-primary mb-3">
      <div class="card-body"><h5>Students</h5><p class="display-6"><?= $total_students ?></p></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-success mb-3">
      <div class="card-body"><h5>Occupancy Rate</h5><p class="display-6"><?= $occ_rate ?>%</p></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning mb-3">
      <div class="card-body"><h5>Total Rooms</h5><p class="display-6"><?= $total_rooms ?></p></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-danger mb-3">
      <div class="card-body"><h5>Revenue (This Month)</h5><p class="display-6">Rs. <?= $revenue ?></p></div>
    </div>
  </div>
</div>

<!-- Booking trends chart -->
<div class="card p-3 mt-4">
  <h5>Bookings by Hostel</h5>
  <canvas id="hostelChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('../admin/get_hostel_data.php')
  .then(response => response.json())
  .then(data => {
    const ctx = document.getElementById('hostelChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Number of Bookings',
          data: data.values,
          backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }]
      }
    });
  });
</script>

<!-- Recent bookings -->
<h5 class="mt-4">Recent Bookings</h5>
<table class="table table-sm">
<thead><tr><th>ID</th><th>Student</th><th>Room</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
<?php
$recent = mysqli_query($conn, "SELECT b.id, s.full_name, r.room_number, b.status, b.booking_date FROM bookings b JOIN students s ON b.student_id=s.id JOIN rooms r ON b.room_id=r.id ORDER BY b.created_at DESC LIMIT 5");
while($row = mysqli_fetch_assoc($recent)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['full_name']}</td><td>{$row['room_number']}</td><td>{$row['status']}</td><td>{$row['booking_date']}</td></tr>";
}
?>
</tbody>
</table>
<?php include '../includes/footer.php'; ?>