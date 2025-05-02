<?php
include '../db.php';

if (isset($_POST['register'])) {
    $society_id = $_POST['society_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO Users (society_id, name, email, password_hash, role, phone)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $society_id, $name, $email, $password, $role, $phone);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!-- Registration Form -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="style.css" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
  <title>Register User</title>
  <script>
    function toggleMenu() {
      const menu = document.getElementById('sidebar');
      menu.classList.toggle('d-none');
    }
  </script>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-light bg-light justify-content-between px-3">
    <div class="d-flex align-items-center">
      <img src="../Images/logo.png" alt="Logo" width="50" height="50">
      <span class="d-md-none ml-3" onclick="toggleMenu()" style="font-size: 28px; cursor: pointer;">☰</span>
    </div>
    <div class="d-none d-md-flex">
      <a href="../logout.php" class="nav-link">Logout</a>
      <a href="about_us.php" class="nav-link">About Us</a>
      <a href="contact_us.php" class="nav-link">Contact Us</a>
    </div>
  </nav>

  <!-- Main Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div id="sidebar" class="col-md-2 bg-secondary text-white py-3 d-block">
        <a href="maintanance_bill.php" class="d-block text-white py-2">Maintenance Bill</a>
        <a href="manage_apartment.php" class="d-block text-white py-2">Manage Apartment</a>
        <a href="manage_complaint.php" class="d-block text-white py-2">Manage Complaint</a>
        <a href="notification.php" class="d-block text-white py-2">Notification</a>
        <a href="view_bill.php" class="d-block text-white py-2">View Bill</a>
        <a href="register.php" class="d-block text-white py-2">Register</a>
        <a href="visitor_approval.php" class="d-block text-white py-2">Visitor Approval</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-9 p-4">
        <h4 class="mb-4">Register User</h4>
        <form method="POST" action="">
          <div class="form-group">
            <label>Society ID:</label>
            <input type="number" name="society_id" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Role:</label>
            <select name="role" class="form-control" required>
              <option value="Admin">Admin</option>
              <option value="Member">Member</option>
              <option value="Security Guard">Security Guard</option>
            </select>
          </div>
          <div class="form-group">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control">
          </div>
          <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="text-center mt-5 bg-light py-3">
    <p>All rights are reserved by CHSMITHRA</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
