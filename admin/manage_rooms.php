<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
$hostel_id = isset($_GET['hostel_id']) ? (int)$_GET['hostel_id'] : 0;
// Add room
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $floor = $_POST['floor'];
    $type = $_POST['room_type'];
    $ac = isset($_POST['has_ac']) ? 1 : 0;
    $capacity = $_POST['capacity'];
    $price = $_POST['price'];
    mysqli_query($conn, "INSERT INTO rooms (hostel_id, room_number, floor, room_type, has_ac, capacity, price_per_month) VALUES ($hostel_id, '$room_number', $floor, '$type', $ac, $capacity, $price)");
    header("Location: manage_rooms.php?hostel_id=$hostel_id"); exit;
}
$rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE hostel_id=$hostel_id");
$hostel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM hostels WHERE id=$hostel_id"));
?>
<h2>Manage Rooms - <?= $hostel['name'] ?? 'Unknown' ?></h2>
<a href="manage_hostels.php" class="btn btn-secondary mb-3">Back to Hostels</a>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRoom">Add Room</button>
<table class="table">
<thead><tr><th>Room No</th><th>Floor</th><th>Type</th><th>AC</th><th>Capacity</th><th>Occupied</th><th>Price</th><th>Status</th></tr></thead>
<tbody>
<?php while($r = mysqli_fetch_assoc($rooms)): ?>
<tr><td><?= $r['room_number'] ?></td><td><?= $r['floor'] ?></td><td><?= $r['room_type'] ?></td><td><?= $r['has_ac']?'Yes':'No' ?></td><td><?= $r['capacity'] ?></td><td><?= $r['current_occupancy'] ?></td><td><?= $r['price_per_month'] ?></td><td><?= $r['status'] ?></td></tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- Modal Add Room -->
<div class="modal fade" id="addRoom" tabindex="-1">
  <div class="modal-dialog">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header"><h5>Add Room</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <input name="room_number" placeholder="Room Number" class="form-control mb-2" required>
          <input name="floor" type="number" placeholder="Floor" class="form-control mb-2" required>
          <select name="room_type" class="form-control mb-2"><option>Single</option><option>Double</option><option>Triple</option><option>Dormitory</option></select>
          <label><input type="checkbox" name="has_ac"> AC</label>
          <input name="capacity" type="number" placeholder="Capacity" class="form-control mb-2" required>
          <input name="price" type="number" step="0.01" placeholder="Price/Month" class="form-control mb-2" required>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>