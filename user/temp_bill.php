<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT apartment_id FROM Apartments WHERE owner_id = ? OR tenant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$apartment = $result->fetch_assoc();

if (!$apartment) {
    die("No apartment found for this user.");
}

$apartment_id = $apartment['apartment_id'];


$bill_sql = "SELECT * FROM Maintenance WHERE apartment_id = ? ORDER BY due_date DESC LIMIT 1";
$bill_stmt = $conn->prepare($bill_sql);
$bill_stmt->bind_param("i", $apartment_id);
$bill_stmt->execute();
$bill_result = $bill_stmt->get_result();
$bill = $bill_result->fetch_assoc();

if (!$bill) {
    die("No maintenance bill available.");
}
$handle = fopen("php://stdin", "r");
$input = trim(fgets($handle));
if (strtolower($input) === 'yes') {
    $update_sql = "UPDATE Maintenance SET status = 'Paid' WHERE maintenance_id = ? AND apartment_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $bill['maintenance_id'], $apartment_id);
    if ($update_stmt->execute()) {
        echo " Payment status updated to 'Paid'.\n";
    } else {
        echo " Failed to update payment status.\n";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Bill</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .qr-code {
            max-width: 200px;
            margin-top: 20px;
        }
        .bill-info {
            margin-top: 30px;
        }
    </style>
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
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Status</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Maintenance Bill</h2>

        <!-- Bill Information -->
        <div class="alert alert-info">
            <strong>Water Bill:</strong> ₹500<br>
            <strong>Electricity Bill:</strong> ₹300<br>
            <strong>Maintenance Charge:</strong> ₹150<br>
            <strong>Total Amount:</strong> ₹950<br>
            <strong>Due Date:</strong> 2025-05-15<br>
            <strong>Status:</strong> <span id="status">Unpaid</span><br>
        </div>

        <!-- QR Code Section -->
        <div class="alert alert-warning">
            <p>Scan the QR code image available below to make the payment.</p>
            <img src="../Images/qr_code.png" class="qr-code" alt="QR Code">
        </div>

        <!-- Payment Confirmation -->
        <button type="button" class="btn btn-success" onclick="markAsPaid()">Mark as Paid</button>

        <!-- Payment Status Message -->
        <div id="paymentStatusMessage" class="mt-3"></div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

<script>
    // Function to simulate marking the bill as paid
    function markAsPaid() {
        // Check if the bill is already paid
        var status = document.getElementById('status').innerText;

        if (status === 'Paid') {
            alert("This bill is already marked as paid.");
        } else {
            // Simulate payment update
            document.getElementById('status').innerText = 'Paid';
            document.getElementById('paymentStatusMessage').innerHTML = '<div class="alert alert-success">✅ Payment status updated to "Paid".</div>';
        }
    }
</script>

</body>
</html>
