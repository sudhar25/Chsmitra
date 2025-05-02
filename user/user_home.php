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
    <title>User Dashboard</title>
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
    <h1>Welcome, User!</h1>
    <p>You have access to all society management features.</p>
    

    <!-- Row 1: 3 Blocks -->
    <div style="display: flex; justify-content: space-around; margin-top: 30px;">
        <div style="width: 30%;">
            <img src="notifications.png" alt="Notifications" width="100%">
            <p>Check your latest notifications</p>
            <button onclick="location.href='view_notifications.php'">Go</button>
        </div>

        <div style="width: 30%;">
            <img src="complaints.png" alt="Complaints" width="100%">
            <p>Submit or view complaints</p>
            <button onclick="location.href='complaints.php'">Go</button>
        </div>

        <div style="width: 30%;">
            <img src="bills.png" alt="Bill Payments" width="100%">
            <p>View and pay your bills</p>
            <button onclick="location.href='bill_payments.php'">Go</button>
        </div>
    </div>

    <!-- Row 2: 2 Blocks -->
    <div style="display: flex; justify-content: space-evenly; margin-top: 30px;">
        <div style="width: 30%;">
            <img src="profile.png" alt="Profile" width="100%">
            <p>View or edit your profile</p>
            <button onclick="location.href='profile.php'">Go</button>
        </div>

        <div style="width: 30%;">
            <img src="services.png" alt="Services" width="100%">
            <p>Access society services</p>
            <button onclick="location.href='services.php'">Go</button>
        </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; position: fixed; bottom: 0; width: 100%; background-color: #f1f1f1; padding: 10px;">
    <p>All rights are reserved by</p>
</footer>

</body>
</html>
