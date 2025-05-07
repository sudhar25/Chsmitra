<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member') {
    header("Location: ../login.php");
    exit();
}
include '../db.php';
// Get apartment ID of the user (owner or tenant)
$sql = "SELECT a.apartment_id, a.society_id
        FROM Apartments a
        WHERE a.owner_id = ? OR a.tenant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$apartment = $result->fetch_assoc();

if (!$apartment) {
    echo "No apartment found for the user.";
    exit;
}

$apartment_id = $apartment['apartment_id'];
$society_id = $apartment['society_id'];

// Get latest unpaid bill
$bill_sql = "SELECT * FROM Maintenance 
             WHERE apartment_id = ? AND status = 'Pending'
             ORDER BY due_date DESC LIMIT 1";
$bill_stmt = $conn->prepare($bill_sql);
$bill_stmt->bind_param("i", $apartment_id);
$bill_stmt->execute();
$bill_result = $bill_stmt->get_result();
$bill = $bill_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bill</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="style.css">

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
                "key": "YOUR_RAZORPAY_KEY", // Replace with your Razorpay Key
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: lightblue;">
    <div class="d-flex align-items-center">
        <img src="../Images/logo.png" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem;">☰</div>
    </div>
    <div class="d-flex">
    <a href="../logout.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Logout</a>
        <a href="user_home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Member</a>
        <a href="../home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Home</a>
    </div>
</nav>

<!-- Layout -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div id="menu" class="d-flex flex-column text-white p-3" style="min-width: 200px; background-color: #336699;">
    <a href="temp_bill.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Pay Bill</a>

        <a href="visitor_approval.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Approval Request</a>

        <a href="complaints.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Complaint</a>

        <a href="user_profile.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Profile</a>
        
        <a href="visitor_status.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Status</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Maintenance Bill</h2>

        <?php if ($bill): ?>
        <div id="bill-details" class="border rounded p-4 bg-light shadow-sm">
            <p><strong>Apartment ID:</strong> <?= $apartment_id ?></p>
            <p><strong>Due Date:</strong> <?= $bill['due_date'] ?></p>
            <p><strong>Water Bill:</strong> ₹<?= $bill['water_bill'] ?></p>
            <p><strong>Electricity Bill:</strong> ₹<?= $bill['electricity_bill'] ?></p>
            <p><strong>Maintenance Charge:</strong> ₹<?= $bill['maintenance_charge'] ?></p>
            <p><strong>Total:</strong> ₹<?= $bill['amount'] ?></p>
            <p><strong>Status:</strong> <?= $bill['status'] ?></p>

            <?php if ($bill['status'] == 'Pending'): ?>
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

</body>
</html>
