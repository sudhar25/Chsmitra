<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
include '../db.php';

if (isset($_POST['register'])) {
  $society_id = $_POST['society_id'];
  $role = $_POST['role'];

  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $phone = $_POST['phone'];

  // Skip apartment logic for Admin or Security Guard
  if ($role === 'Member') {
      $apartment_number = $_POST['apartment_number'];
      $status = $_POST['status']; // 'Owner' or 'Tenant'

      // First, get the apartment_id from apartment_number and society_id
      $stmt = $conn->prepare("SELECT apartment_id, owner_id, tenant_id FROM Apartments WHERE society_id = ? AND apartment_number = ?");
      $stmt->bind_param("is", $society_id, $apartment_number);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 0) {
          echo "Apartment not found in this society.";
          exit;
      }

      $row = $result->fetch_assoc();
      $apartment_id = $row['apartment_id'];

      // Ownership check
      if ($status === 'Owner' && !is_null($row['owner_id'])) {
          echo "This apartment already has an owner!";
          exit;
      }

      if ($status === 'Tenant' && !is_null($row['tenant_id'])) {
          echo "This apartment already has a tenant!";
          exit;
      }
  }

  // Insert the user
  $sql = "INSERT INTO Users (society_id, name, email, password_hash, role, phone) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isssss", $society_id, $name, $email, $password, $role, $phone);

  if ($stmt->execute()) {
      $user_id = $stmt->insert_id;

      // Only update Apartments if user is a Member
      if ($role === 'Member') {
          if ($status === 'Owner') {
              $update = $conn->prepare("UPDATE Apartments SET owner_id = ? WHERE apartment_id = ?");
          } else {
              $update = $conn->prepare("UPDATE Apartments SET tenant_id = ? WHERE apartment_id = ?");
          }
          $update->bind_param("ii", $user_id, $apartment_id);
          $update->execute();
      }

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
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
  <title>Register User</title>
  <script>
    function toggleMenu() {
      const menu = document.getElementById('sidebar');
      menu.classList.toggle('d-none');
    }
  </script>
</head>
<body>

<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: lightblue;">
    <div class="d-flex align-items-center">
    <img src="../Images/logo.png" alt="Logo" 
    style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem;">☰</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Logout</a>
        <a href="admin_home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Admin</a>
        <a href="../home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Home</a>
    </div>
</nav>

<!-- Layout: Sidebar + Main Content -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar Menu -->
    <div id="menu" class="d-flex flex-column text-white p-3"
         style="min-width: 200px; background-color: #336699;">
        <a href="maintanance_bill.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Maintenance Bill</a>

           <a href="manage_apartment.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Manage Apartment</a>

        <a href="manage_complaint.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Manage Complaint</a>

        <a href="notification.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Notification</a>

        <a href="view_bill.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Bill</a>

        <a href="register.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Register User</a>

        <a href="visitor_approval.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Approval</a>
        
           <a href="register_society.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Register Society</a> 
           
           <a href="view_details.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Details</a>   
      </div>


      <!-- Main Content -->
      <div class="col-md-9 p-4">
  <h2 class="mb-4">Register User</h2>
  <form method="POST" action="">
    <div class="form-group">
      <label>Society ID:</label>
      <input type="number" name="society_id" class="form-control" required>
    </div>
    <div class="form-group apartment-fields">
      <label>Apartment Number:</label>
      <input type="number" name="apartment_number" class="form-control">
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
      <select name="role" class="form-control" id="role-select" required>
        <option value="Admin">Admin</option>
        <option value="Member">Member</option>
        <option value="Security Guard">Security Guard</option>
      </select>
    </div>
    <div class="form-group apartment-fields">
      <label>Ownership Status:</label>
      <select name="status" class="form-control">
        <option value="" disabled selected>Select Status</option>
        <option value="Owner">Owner</option>
        <option value="Tenant">Tenant</option>
      </select>
    </div>
    <div class="form-group">
      <label>Phone:</label>
      <input type="text" name="phone" class="form-control">
    </div>
    <button type="submit" name="register" class="btn btn-primary">Register</button>
  </form>
</div>

<script>
  const roleSelect = document.getElementById('role-select');
  const apartmentFields = document.querySelectorAll('.apartment-fields');

  function toggleApartmentFields() {
    const role = roleSelect.value;
    apartmentFields.forEach(field => {
      field.style.display = (role === 'Member') ? 'block' : 'none';
      field.querySelector('input, select').required = (role === 'Member');
    });
  }

  roleSelect.addEventListener('change', toggleApartmentFields);
  window.addEventListener('DOMContentLoaded', toggleApartmentFields);
</script>


  <!-- Footer -->
  <footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>


  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
