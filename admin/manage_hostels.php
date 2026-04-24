<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
// Add hostel
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = $_POST['type'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    mysqli_query($conn, "INSERT INTO hostels (name, type, address, description) VALUES ('$name', '$type', '$address', '$desc')");
}
$hostels = mysqli_query($conn, "SELECT * FROM hostels");
?>
<h2>Manage Hostels</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add Hostel</button>
<table class="table table-bordered">
<thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Address</th><th>Action</th></tr></thead>
<tbody>
<?php while($h = mysqli_fetch_assoc($hostels)): ?>
<tr>
    <td><?= $h['id'] ?></td><td><?= $h['name'] ?></td><td><?= $h['type'] ?></td><td><?= $h['address'] ?></td>
    <td><a href="manage_rooms.php?hostel_id=<?= $h['id'] ?>" class="btn btn-sm btn-info">Rooms</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Hostel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3"><input name="name" class="form-control" placeholder="Hostel Name" required></div>
          <div class="mb-3"><select name="type" class="form-control"><option>Boys</option><option>Girls</option><option>Mixed</option></select></div>
          <div class="mb-3"><input name="address" class="form-control" placeholder="Address"></div>
          <div class="mb-3"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
        </div>
        <div class="modal-footer"><button type="submit" name="add" class="btn btn-success">Save</button></div>
      </div>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>