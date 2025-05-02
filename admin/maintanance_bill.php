<?php
// Database Connection
include '../db.php';

// Handle Form Submission
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
    <link href="style.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function calculateTotal() {
            let water = parseFloat(document.getElementById('water').value) || 0;
            let electricity = parseFloat(document.getElementById('electricity').value) || 0;
            let maintenance = parseFloat(document.getElementById('maintenance').value) || 0;
            document.getElementById('total').value = water + electricity + maintenance;
        }

        function printForm() {
            window.print();
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
<nav class="d-flex justify-content-between align-items-center bg-light px-3 py-2">
    <div class="d-flex align-items-center">
    <img src="../Images/logo.png" alt="Logo" width="50" height="50">
        <div class="hamburger ml-3" onclick="toggleMenu()">☰</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link">Logout</a>
        <a href="about_us.php" class="nav-link">About Us</a>
        <a href="contact_us.php" class="nav-link">Contact Us</a>
    </div>
</nav>

<!-- Layout: Sidebar + Main Content -->
<div class="layout d-flex">
    <!-- Sidebar Menu -->
    <div id="menu" class="d-flex flex-column bg-secondary text-white p-3" style="min-width: 200px;">
        <a href="maintanance_bill.php" class="text-white py-1">Maintenance Bill</a>
        <a href="manage_apartment.php" class="text-white py-1">Manage Apartment</a>
        <a href="manage_complaint.php" class="text-white py-1">Manage Complaint</a>
        <a href="notification.php" class="text-white py-1">Notification</a>
        <a href="view_bill.php" class="text-white py-1">View Bill</a>
        <a href="register.php" class="text-white py-1">Register</a>
        <a href="visitor_approval.php" class="text-white py-1">Visitor Approval</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h1><b>CHSMITHRA</b></h1>
        <h2>Bill Generator</h2>
        <form method="POST">
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

            <p id="info-text">[Add your paragraph here later]</p>

            <button type="submit" class="btn btn-primary">Generate Bill</button>
            <button type="button" class="btn btn-secondary" onclick="printForm()">Print</button>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="text-center bg-light py-3 mt-5">
    <p>All rights are reserved by CHSMITHRA</p>
</footer>

</body>
</html>
