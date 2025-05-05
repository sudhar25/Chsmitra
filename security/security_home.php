<?php
session_start();

//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Security Guard') {
  //  header("Location: login.php");
    //exit();
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Security Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Left side: Logo -->
        <a class="navbar-brand" href="#">
            <img src="../Images/logo.png" alt="Logo" 
                 style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        </a>

        <!-- Right side: Links -->
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about_us.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../home.php">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="container mt-4">
    <h1>Welcome, Security!</h1>
    <p>You have access to all society management features.</p>

    <!-- Grid blocks -->
    <div class="row">
        <!-- Block 1 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/visitor.png" class="card-img-top" alt="Guest Approval">
                <div class="card-body">
                    <p class="card-text"><strong>Approve Visitor</strong></p>
                    <a href="guest_approval.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 2 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Notification.png" class="card-img-top" alt="Notification">
                <div class="card-body">
                    <p class="card-text"><strong>Send Notification</strong></p>
                    <a href="notification_system.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 3 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Manage Complaint.png" class="card-img-top" alt="Complaints">
                <div class="card-body">
                    <p class="card-text"><strong>Manage Complaints</strong></p>
                    <a href="complaint_management.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Notification.png" class="card-img-top" alt="view details">
                <div class="card-body">
                    <p class="card-text"><strong>View Details of Society</strong></p>
                    <a href="view_details.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>


</body>
</html>
