<?php
session_start();
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
  //  header("Location: login.php");
  //  exit();
//}
include '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $society_id = $_POST['society_id'];
    $due_date = $_POST['due_date'];
    $water_bill = floatval($_POST['water_bill']);
    $electricity_bill = floatval($_POST['electricity_bill']);
    $maintenance_charge = floatval($_POST['maintenance_charge']);
    $total_amount = $water_bill + $electricity_bill + $maintenance_charge;

    // Get all apartments in the society
    $apartment_sql = "SELECT * FROM Apartments WHERE society_id = ?";
    $stmt = $conn->prepare($apartment_sql);
    $stmt->bind_param("i", $society_id);
    $stmt->execute();
    $apartments_result = $stmt->get_result();

    while ($apartment = $apartments_result->fetch_assoc()) {
        $apartment_id = $apartment['apartment_id'];

        // Insert bill for each apartment
        $insert_sql = "INSERT INTO Maintenance 
            (society_id, apartment_id, amount, due_date, water_bill, electricity_bill, maintenance_charge)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("iidssss", $society_id, $apartment_id, $total_amount, $due_date, $water_bill, $electricity_bill, $maintenance_charge);
        $stmt_insert->execute();

        // Get owner/tenant to notify
        $user_id = $apartment['tenant_id'] ?? $apartment['owner_id'];
        if ($user_id) {
            $message = "New bill generated: Water ₹$water_bill, Electricity ₹$electricity_bill, Maintenance ₹$maintenance_charge. Total: ₹$total_amount. Due: $due_date";

            $notify_sql = "INSERT INTO Notifications (society_id, user_id, message) 
                           VALUES (?, ?, ?)";
            $stmt_notify = $conn->prepare($notify_sql);
            $stmt_notify->bind_param("iis", $society_id, $user_id, $message);
            $stmt_notify->execute();
        }
    }

    echo "<script>alert('Bills generated and notifications sent.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Bill - Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    
    <script>
        function calculateTotal() {
            let water = parseFloat(document.getElementById('water').value) || 0;
            let electricity = parseFloat(document.getElementById('electricity').value) || 0;
            let maintenance = parseFloat(document.getElementById('maintenance').value) || 0;
            document.getElementById('total').value = water + electricity + maintenance;
        }

        function printForm() {
            const printContent = document.getElementById('printArea').innerHTML;
            const original = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = original;
        }

        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
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

<!-- Layout: Sidebar + Main Content -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar Menu -->
    <div id="menu" class="d-flex flex-column text-white p-3"
         style="min-width: 200px; background-color: #336699;">
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
    <div class="flex-grow-1 p-4">
    <h2>Bill Generator</h2>
        <div id="printArea">
        <form method="POST">
            <h1><strong>Maintanance Bill</strong></h1>
            <h2>CHSmitra</h2>
            <div class="form-group">
                <label for="society_id">Society ID:</label>
                <input type="number" class="form-control" id="society_id" name="society_id" required>
            </div>

            <div class="form-group">
                <label for="water">Water Bill (₹):</label>
                <input type="number" class="form-control" id="water" name="water_bill" step="0.01" oninput="calculateTotal()" required>
            </div>

            <div class="form-group">
                <label for="electricity">Electricity Bill (₹):</label>
                <input type="number" class="form-control" id="electricity" name="electricity_bill" step="0.01" oninput="calculateTotal()" required>
            </div>

            <div class="form-group">
                <label for="maintenance">Maintenance Charge (₹):</label>
                <input type="number" class="form-control" id="maintenance" name="maintenance_charge" step="0.01" oninput="calculateTotal()" required>
            </div>

            <div class="form-group">
                <label for="total">Total Amount (₹):</label>
                <input type="number" class="form-control" id="total" readonly>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" class="form-control" name="due_date" required>
            </div>

            <p id="info-text">Please ensure that the total bill amount is paid on or before the due date mentioned above.<br> 
                <br>A fine of ₹100 will be added for every week of delay after the due date.<br> 
                <br>Payments can be made online or at the society office during working hours.<br>
                <br>Residents are requested to retain the payment receipt for future reference.<br> 
                <br>Disputes related to bill amounts must be reported within 7 days.<br>
                <br>For any queries, please contact the society office. <br>
                <br>Timely payments help maintain cleanliness, security, and other essential services in the society.<br></p>
            <p>This is a computer generated bill and does not require a signature.</p>
            <button type="submit" class="btn btn-primary">Generate Bill</button>
            <button type="button" class="btn btn-secondary" onclick="printForm()">Print</button>
        </form>
    </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>


</body>
</html>
