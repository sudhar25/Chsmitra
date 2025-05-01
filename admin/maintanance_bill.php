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
<html>
<head>
    <title>Generate Bill - Admin</title>
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
    </script>
</head>
<body>

<h1><b>CHSMITHRA</b></h1>

<h2>Bill Generator</h2>
<form method="POST">
    <label>Society ID:</label>
    <input type="number" name="society_id" required>

    <label>Water Bill (₹):</label>
    <input type="number" id="water" name="water_bill" step="0.01" oninput="calculateTotal()" required>

    <label>Electricity Bill (₹):</label>
    <input type="number" id="electricity" name="electricity_bill" step="0.01" oninput="calculateTotal()" required>

    <label>Maintenance Charge (₹):</label>
    <input type="number" id="maintenance" name="maintenance_charge" step="0.01" oninput="calculateTotal()" required>

    <label>Total Amount (₹):</label>
    <input type="number" id="total" readonly>

    <label>Due Date:</label>
    <input type="date" name="due_date" required>

    <p id="info-text">[Add your paragraph here later]</p>

    <button type="submit">Generate Bill</button>
    <button type="button" onclick="printForm()">Print</button>
</form>

</body>
</html>
