//<?php
//session_start();
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
  //  header("Location: login.php");
  //  exit();
//}
//?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Navbar -->
    <nav>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
        <!-- Left side: Logo -->
        <div style="flex-shrink: 0;">
            <img src="logo_placeholder.png" alt="Logo" width="50" height="50">
        </div>

        <!-- Right side: Links -->
        <div style="display: flex; gap: 15px;">
            <a href="../logout.php">Logout</a>
            <a href="about_us.php">About Us</a>
            <a href="contact_us.php">Contact Us</a>
        </div>
    </div>
</nav>

    <!-- Main content -->
    <h1>Welcome, Admin!</h1>
    <p>You have access to all society management features.</p>
    

    <!-- Grid blocks -->
    <div style="display: flex; flex-wrap: wrap;">
        <!-- Block 1 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image1_placeholder.png" alt="Image 1" width="100%">
            <p>Manage Users</p>
            <button onclick="location.href='manage_users.php'">Go</button>
        </div>

        <!-- Block 2 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image2_placeholder.png" alt="Image 2" width="100%">
            <p>Register New User</p>
            <button onclick="location.href='register.php'">Go</button>
        </div>

        <!-- Block 3 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image3_placeholder.png" alt="Image 3" width="100%">
            <p>Manage Apartments</p>
            <button onclick="location.href='manage_apartment.php'">Go</button>
        </div>

        <!-- Block 4 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image4_placeholder.png" alt="Image 4" width="100%">
            <p>Handle Complaints</p>
            <button onclick="location.href='manage_complaint.php'">Go</button>
        </div>

        <!-- Block 5 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image5_placeholder.png" alt="Image 5" width="100%">
            <p>Send Notifications</p>
            <button onclick="location.href='notification.php'">Go</button>
        </div>

        <!-- Block 6 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image6_placeholder.png" alt="Image 6" width="100%">
            <p>Visitor Logs</p>
            <button onclick="location.href='visitor_logs.php'">Go</button>
        </div>

        <!-- Block 7 -->
        <div style="width: 30%; margin: 1%;">
            <img src="image7_placeholder.png" alt="Image 7" width="100%">
            <p>Maintenance Payments</p>
            <button onclick="location.href='maintenance.php'">Go</button>
        </div>

        <!-- Extra Block 8 (optional) -->
        <div style="width: 30%; margin: 1%;">
            <img src="image8_placeholder.png" alt="Image 8" width="100%">
            <p>Coming Soon</p>
            <button onclick="location.href='#'">Go</button>
        </div>

        <!-- Extra Block 9 (optional) -->
        <div style="width: 30%; margin: 1%;">
            <img src="image9_placeholder.png" alt="Image 9" width="100%">
            <p>Coming Soon</p>
            <button onclick="location.href='#'">Go</button>
        </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; position: fixed; bottom: 0; width: 100%; background-color: #f1f1f1; padding: 10px;">
    <p>All rights are reserved by</p>
</footer>

</body>
</html>
