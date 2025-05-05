<?php

session_start();
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
  //  header("Location: login.php");
  //  exit();
//}
include '../db.php';

if (isset($_POST['register_society'])) {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);

    // Validate input
    if (empty($name) || empty($address)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO Societies (name, address) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $address);

    if ($stmt->execute()) {
        echo "<div style='margin: 20px; color: green;'>Society registered successfully!</div>";
        
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
  <title>Register Society</title>
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

<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div id="menu" class="d-flex flex-column text-white p-3"
         style="min-width: 200px; background-color: #336699; height: 100%;">
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
        <h2 class="mb-4">Register Society</h2>
        <form method="POST" action="">
          <div class="form-group">
            <label>Society Name:</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Address:</label>
            <textarea name="address" class="form-control" rows="3" required></textarea>
          </div>
          <button type="submit" name="register_society" class="btn btn-primary">Register Society</button>
        </form>
    </div>
</div>

<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

