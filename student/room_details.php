<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
$hostel_id = isset($_GET['hostel_id']) ? (int)$_GET['hostel_id'] : 0;
$hostel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM hostels WHERE id=$hostel_id"));
if (!$hostel) { echo "<div class='alert alert-danger'>Hostel not found.</div>"; include '../includes/footer.php'; exit; }
$rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE hostel_id=$hostel_id AND status != 'Maintenance' ORDER BY floor, room_number");
?>
<h3>Rooms in <?= $hostel['name'] ?></h3>
<table class="table table-bordered">
<thead>
<tr><th>Room No</th><th>Floor</th><th>Type</th><th>AC</th><th>Capacity</th><th>Occupied</th><th>Available</th><th>Price/Month</th><th>Status</th><th>Action</th></tr>
</thead>
<tbody>
<?php while($room = mysqli_fetch_assoc($rooms)): 
    $available = $room['capacity'] - $room['current_occupancy'];
?>
<tr>
    <td><?= $room['room_number'] ?></td>
    <td><?= $room['floor'] ?></td>
    <td><?= $room['room_type'] ?></td>
    <td><?= $room['has_ac'] ? 'Yes' : 'No' ?></td>
    <td><?= $room['capacity'] ?></td>
    <td><?= $room['current_occupancy'] ?></td>
    <td><?= $available ?></td>
    <td>Rs. <?= $room['price_per_month'] ?></td>
    <td><?= $room['status'] ?></td>
    <td>
        <?php if($available > 0 && $room['status'] != 'Maintenance'): ?>
            <a href="booking_request.php?room_id=<?= $room['id'] ?>" class="btn btn-sm btn-success">Book Now</a>
        <?php else: ?>
            <span class="text-muted">Full</span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<a href="browse_hostels.php" class="btn btn-secondary">Back to Hostels</a>
<?php include '../includes/footer.php'; ?>