<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
// Update complaint status
if (isset($_POST['update_complaint'])) {
    $comp_id = (int)$_POST['complaint_id'];
    $status = $_POST['status'];
    $remarks = mysqli_real_escape_string($conn, $_POST['admin_remarks']);
    mysqli_query($conn, "UPDATE complaints SET status='$status', admin_remarks='$remarks' WHERE id=$comp_id");
}
$complaints = mysqli_query($conn, "SELECT c.*, s.full_name FROM complaints c JOIN students s ON c.student_id=s.id ORDER BY c.created_at DESC");
?>
<h2>Complaints</h2>
<table class="table">
<thead><tr><th>ID</th><th>Student</th><th>Subject</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
<tbody>
<?php while($c = mysqli_fetch_assoc($complaints)): ?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['full_name'] ?></td>
    <td><?= $c['subject'] ?></td>
    <td><?= $c['status'] ?></td>
    <td><?= $c['created_at'] ?></td>
    <td>
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#compModal<?= $c['id'] ?>">View/Update</button>
        <div class="modal fade" id="compModal<?= $c['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <form method="post">
              <div class="modal-content">
                <div class="modal-header"><h5>Complaint #<?= $c['id'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                  <p><strong>Description:</strong> <?= $c['description'] ?></p>
                  <input type="hidden" name="complaint_id" value="<?= $c['id'] ?>">
                  <select name="status" class="form-control mb-2">
                    <option <?= $c['status']=='Open'?'selected':'' ?>>Open</option>
                    <option <?= $c['status']=='In Progress'?'selected':'' ?>>In Progress</option>
                    <option <?= $c['status']=='Resolved'?'selected':'' ?>>Resolved</option>
                  </select>
                  <textarea name="admin_remarks" class="form-control" placeholder="Admin remarks"><?= $c['admin_remarks'] ?></textarea>
                </div>
                <div class="modal-footer"><button type="submit" name="update_complaint" class="btn btn-primary">Update</button></div>
              </div>
            </form>
          </div>
        </div>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php include '../includes/footer.php'; ?>