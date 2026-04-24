<?php
require_once 'config/db.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $reg_no = mysqli_real_escape_string($conn, $_POST['registration_number']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $year = (int)$_POST['year_of_study'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "INSERT INTO students (full_name, email, phone, gender, registration_number, course, year_of_study, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssssssis', $full_name, $email, $phone, $gender, $reg_no, $course, $year, $password);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['student_id'] = mysqli_insert_id($conn);
        $_SESSION['student_name'] = $full_name;
        header("Location: student/dashboard.php");
        exit;
    } else {
        $error = "Registration failed. Email or Registration Number might already exist.";
    }
}
include 'includes/header.php';
?>
<h2>Student Registration</h2>
<?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="post">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Full Name</label>
      <input type="text" name="full_name" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="">Select</option>
        <option>Male</option>
        <option>Female</option>
        <option>Other</option>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Registration Number</label>
      <input type="text" name="registration_number" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Course</label>
      <input type="text" name="course" class="form-control" required>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Year of Study</label>
      <input type="number" name="year_of_study" class="form-control" min="1" max="5" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
  </div>
  <button type="submit" class="btn btn-success">Register</button>
  <p class="mt-2">Already registered? <a href="login.php">Login</a></p>
</form>
<?php include 'includes/footer.php'; ?>