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
    <title>Admin Dashboard</title>
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
            <img src="../Images/logo.png" alt="Logo" width="50" height="50">
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
                <img src="image1_placeholder.png" class="card-img-top" alt="Image 1">
                <div class="card-body">
                    <p class="card-text">Manage Users</p>
                    <a href="manage_users.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 2 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image2_placeholder.png" class="card-img-top" alt="Image 2">
                <div class="card-body">
                    <p class="card-text">Register New User</p>
                    <a href="register.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 3 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image3_placeholder.png" class="card-img-top" alt="Image 3">
                <div class="card-body">
                    <p class="card-text">Manage Apartments</p>
                    <a href="manage_apartment.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 4 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image4_placeholder.png" class="card-img-top" alt="Image 4">
                <div class="card-body">
                    <p class="card-text">Handle Complaints</p>
                    <a href="manage_complaint.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 5 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image5_placeholder.png" class="card-img-top" alt="Image 5">
                <div class="card-body">
                    <p class="card-text">Send Notifications</p>
                    <a href="notification.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 6 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image6_placeholder.png" class="card-img-top" alt="Image 6">
                <div class="card-body">
                    <p class="card-text">Visitor Logs</p>
                    <a href="visitor_logs.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 7 -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image7_placeholder.png" class="card-img-top" alt="Image 7">
                <div class="card-body">
                    <p class="card-text">Maintenance Payments</p>
                    <a href="maintenance.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Extra Block 8 (optional) -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image8_placeholder.png" class="card-img-top" alt="Image 8">
                <div class="card-body">
                    <p class="card-text">Coming Soon</p>
                    <a href="#" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Extra Block 9 (optional) -->
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card">
                <img src="image9_placeholder.png" class="card-img-top" alt="Image 9">
                <div class="card-body">
                    <p class="card-text">Coming Soon</p>
                    <a href="#" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center fixed-bottom bg-light py-3">
    <p>All rights are reserved by</p>
</footer>

</body>
</html>
