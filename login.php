<?php
require_once 'config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == 'student') {
        $result = mysqli_query($conn, "SELECT id, password, full_name FROM students WHERE email='$email'");
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['student_id'] = $row['id'];
                $_SESSION['student_name'] = $row['full_name'];
                header("Location: student/dashboard.php");
                exit;
            } else $error = "Invalid password.";
        } else $error = "No student found with this email.";
    } elseif ($role == 'admin') {
        $result = mysqli_query($conn, "SELECT id, password, full_name FROM admins WHERE email='$email' OR username='$email'");
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['full_name'];
                header("Location: admin/dashboard.php");
                exit;
            } else $error = "Invalid password.";
        } else $error = "No admin found.";
    }
}
include 'includes/header.php';
?>
<h2>Login</h2>
<?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="post">
  <div class="mb-3">
    <label>Email (or Admin Username)</label>
    <input type="text" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-control" required>
      <option value="student">Student</option>
      <option value="admin">Admin</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Login</button>
  <p class="mt-2">Don't have an account? <a href="register.php">Register</a></p>
</form>
<?php include 'includes/footer.php'; ?>