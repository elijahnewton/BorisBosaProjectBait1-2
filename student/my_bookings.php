<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
$student_id = $_SESSION['student_id'];
$bookings = mysqli_query($conn, "SELECT b.*, r.room_number, r.room_type, h.name AS hostel_name FROM bookings b JOIN rooms r ON b.room_id = r.id JOIN hostels h ON r.hostel_id = h.id WHERE b.student_id=$student_id ORDER BY b.created_at DESC");
?>
<h2>My Bookings</h2>
<table class="table table-striped">
<thead><tr><th>Hostel</th><th>Room</th><th>Type</th><th>Date</th><th>Check-in</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php while($b = mysqli_fetch_assoc($bookings)): ?>
<tr>
    <td><?= $b['hostel_name'] ?></td>
    <td><?= $b['room_number'] ?></td>
    <td><?= $b['room_type'] ?></td>
    <td><?= $b['booking_date'] ?></td>
    <td><?= $b['expected_checkin'] ?></td>
    <td><span class="badge bg-<?= $b['status']=='Approved'?'success':($b['status']=='Pending'?'warning':'danger') ?>"><?= $b['status'] ?></span></td>
    <td>
        <?php if ($b['status'] == 'Pending'): ?>
            <a href="?cancel=<?= $b['id'] ?>" class="btn btn-sm btn-danger">Cancel</a>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php
// Cancel handling
if (isset($_GET['cancel'])) {
    $booking_id = (int)$_GET['cancel'];
    mysqli_query($conn, "UPDATE bookings SET status='Cancelled' WHERE id=$booking_id AND student_id=$student_id");
    header("Location: my_bookings.php");
    exit;
}
include '../includes/footer.php';
?>