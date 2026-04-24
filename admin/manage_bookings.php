<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';

// Approve/Reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    if ($action == 'approve') {
        mysqli_query($conn, "UPDATE bookings SET status='Approved' WHERE id=$id");
        // increase room occupancy
        $b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT room_id FROM bookings WHERE id=$id"));
        if ($b) mysqli_query($conn, "UPDATE rooms SET current_occupancy = current_occupancy + 1 WHERE id={$b['room_id']}");
    } elseif ($action == 'reject') {
        mysqli_query($conn, "UPDATE bookings SET status='Rejected' WHERE id=$id");
    } elseif ($action == 'cancel') {
        $b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT room_id, status FROM bookings WHERE id=$id"));
        if ($b && $b['status'] == 'Approved') {
            mysqli_query($conn, "UPDATE rooms SET current_occupancy = current_occupancy - 1 WHERE id={$b['room_id']}");
        }
        mysqli_query($conn, "UPDATE bookings SET status='Cancelled' WHERE id=$id");
    }
    header("Location: manage_bookings.php"); exit;
}

$bookings = mysqli_query($conn, "SELECT b.*, s.full_name, r.room_number, h.name AS hostel_name FROM bookings b JOIN students s ON b.student_id=s.id JOIN rooms r ON b.room_id=r.id JOIN hostels h ON r.hostel_id=h.id ORDER BY b.created_at DESC");
?>
<h2>Manage Bookings</h2>
<table class="table table-bordered">
<thead><tr><th>ID</th><th>Student</th><th>Hostel</th><th>Room</th><th>Check-in Date</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php while($book = mysqli_fetch_assoc($bookings)): ?>
<tr>
    <td><?= $book['id'] ?></td>
    <td><?= $book['full_name'] ?></td>
    <td><?= $book['hostel_name'] ?></td>
    <td><?= $book['room_number'] ?></td>
    <td><?= $book['expected_checkin'] ?></td>
    <td><?= $book['status'] ?></td>
    <td>
        <?php if($book['status'] == 'Pending'): ?>
            <a href="?action=approve&id=<?= $book['id'] ?>" class="btn btn-sm btn-success">Approve</a>
            <a href="?action=reject&id=<?= $book['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
        <?php elseif(in_array($book['status'], ['Approved','Pending'])): ?>
            <a href="?action=cancel&id=<?= $book['id'] ?>" class="btn btn-sm btn-warning">Cancel</a>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php include '../includes/footer.php'; ?>