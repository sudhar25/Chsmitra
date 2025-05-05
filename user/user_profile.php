<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'SocietyManagement';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample user ID logic (replace with session-based login)
$user_id = $_SESSION['user_id'] ?? 1;

// Fetch apartment ID for the user
$apartment_query = $conn->prepare("SELECT apartment_id FROM Users WHERE user_id = ?");
$apartment_query->bind_param("i", $user_id);
$apartment_query->execute();
$apartment_result = $apartment_query->get_result();
$apartment_row = $apartment_result->fetch_assoc();
$apartment_id = $apartment_row['apartment_id'] ?? null;

$bill = null;
if ($apartment_id) {
    $stmt = $conn->prepare("SELECT * FROM Maintenance WHERE apartment_id = ? ORDER BY due_date DESC LIMIT 1");
    $stmt->bind_param("i", $apartment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bill = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bill</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar a {
            color: lightblue;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .sidebar {
            min-width: 200px;
            background-color: #336699;
        }
        .nav-link {
            color: #003366 !important;
        }
    </style>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }

        function printBill() {
            window.print();
        }

        function payNow(billId, amount) {
            var options = {
                "key": "YOUR_RAZORPAY_KEY", // Replace with your actual key
                "amount": amount * 100,
                "currency": "INR",
                "name": "CHSMITRA Maintenance",
                "description": "Bill Payment",
                "handler": function (response) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "update_payment_status.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert("Payment Successful");
                            location.reload();
                        }
                    };
                    xhr.send("bill_id=" + billId);
                }
            };
            var rzp = new Razorpay(options);
            rzp.open();
        }
    </script>
</head>
<body>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: lightblue;">
    <div class="d-flex align-items-center">
        <img src="../Images/logo.png" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem;">☰</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link">Logout</a>
        <a href="user_dashboard.php" class="nav-link">Dashboard</a>
        <a href="../home.php" class="nav-link">Home</a>
    </div>
</nav>

<!-- Layout -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div id="menu" class="sidebar d-flex flex-column text-white p-3">
        <a href="my_bill.php" class="py-1">My Bill</a>
        <a href="submit_complaint.php" class="py-1">Submit Complaint</a>
        <a href="view_notifications.php" class="py-1">Notifications</a>
        <a href="edit_profile.php" class="py-1">Edit Profile</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Maintenance Bill</h2>

        <?php if ($bill): ?>
        <div id="bill-details" class="border rounded p-4 bg-light shadow-sm">
            <p><strong>Apartment ID:</strong> <?= htmlspecialchars($apartment_id) ?></p>
            <p><strong>Due Date:</strong> <?= htmlspecialchars($bill['due_date']) ?></p>
            <p><strong>Water Bill:</strong> ₹<?= htmlspecialchars($bill['water_bill']) ?></p>
            <p><strong>Electricity Bill:</strong> ₹<?= htmlspecialchars($bill['electricity_bill']) ?></p>
            <p><strong>Maintenance Charge:</strong> ₹<?= htmlspecialchars($bill['maintenance_charge']) ?></p>
            <p><strong>Total:</strong> ₹<?= htmlspecialchars($bill['amount']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($bill['status']) ?></p>

            <?php if ($bill['status'] === 'Pending'): ?>
                <button class="btn btn-success" onclick="payNow(<?= $bill['maintenance_id'] ?>, <?= $bill['amount'] ?>)">Pay Now</button>
            <?php endif; ?>
            <button class="btn btn-secondary ml-2" onclick="printBill()">Print</button>
        </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">No pending bills found.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
