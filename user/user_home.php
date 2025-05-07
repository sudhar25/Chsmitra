<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Home</title>
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
    <h1>Welcome, Member!</h1>
    <p>You have access to all society management features.</p>

    <!-- Grid blocks -->
    <div class="row">
        <!-- Block 1 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Maintenance payment.png" class="card-img-top" alt="pay bill">
                <div class="card-body">
                    <p class="card-text"><strong>Pay Bill</strong></p>
                    <a href="temp_bill.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 2 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/Manage Complaint.png" class="card-img-top" alt="Complaints">
                <div class="card-body">
                    <p class="card-text"><strong>Submit complaints</strong></p>
                    <a href="complaints.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 3 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/visitor.png" class="card-img-top" alt="visitor request">
                <div class="card-body">
                    <p class="card-text"><strong>Request for Visitor</strong></p>
                    <a href="bill_payments.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 4 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/user profile.png" class="card-img-top" alt="Profile">
                <div class="card-body">
                    <p class="card-text"><strong>View your profile</strong></p>
                    <a href="user_profile.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <!-- Block 5 -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card">
                <img src="../Images/visitor.png" class="card-img-top" alt="Services">
                <div class="card-body">
                    <p class="card-text"><strong>View Visitor Request Status</strong></p>
                    <a href="visitor_status.php" class="btn btn-primary">Go</a>
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
