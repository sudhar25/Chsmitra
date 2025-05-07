<?php
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
    <title>Admin Home</title>
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
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
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
    <h1>Welcome, Admin!</h1>
    <p>You have access to all society management features.</p>

    <!-- Grid blocks -->
    <div class="row">
        <!-- Block 1 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Register.png" class="card-img-top" alt="Image 1">
                <div class="card-body">
                    <p class="card-text"><strong>Register New user</strong></p>
                    <a href="register.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 2 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Manage Apartment.png" class="card-img-top" alt="Image 2">
                <div class="card-body">
                    <p class="card-text"><strong>Manage Apartment</strong></p>
                    <a href="manage_apartment.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 3 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Manage Complaint.png" class="card-img-top" alt="Image 3">
                <div class="card-body">
                    <p class="card-text"><strong>Manage Complaint</strong></p>
                    <a href="manage_complaint.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 4 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Maintenance payment.png" class="card-img-top" alt="Image 4">
                <div class="card-body">
                    <p class="card-text"><strong>Maintanance Bill</strong></p>
                    <a href="maintanance_bill.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 5 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Notification.png" class="card-img-top" alt="Image 5">
                <div class="card-body">
                    <p class="card-text"><strong>Send Notifications</strong></p>
                    <a href="notification.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 6 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/visitor.png" class="card-img-top" alt="Image 6">
                <div class="card-body">
                    <p class="card-text"><strong>Approve Visitor</strong></p>
                    <a href="visitor_approval.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 7 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Maintenance payment.png" class="card-img-top" alt="Image 7">
                <div class="card-body">
                    <p class="card-text"><strong>View Bill</strong></p>
                    <a href="view_bill.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Extra Block 8 (optional) -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/apt register.png" class="card-img-top" alt="Image 8">
                <div class="card-body">
                    <p class="card-text"><strong>Register society</strong></p>
                    <a href="register_society.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Extra Block 9 (optional) -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/view data (1).png" class="card-img-top" alt="Image 9">
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

