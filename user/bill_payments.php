<?php
session_start();
include '../db.php';

$user_id = $_SESSION['user_id']; // Assuming login system stores this

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
<html>
<head>
    <title>My Bill</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function printBill() {
            window.print();
        }

        function payNow(billId, amount) {
            var options = {
                "key": "YOUR_RAZORPAY_KEY", // Replace with your Razorpay Key
                "amount": amount * 100, // Razorpay amount in paisa
                "currency": "INR",
                "name": "CHSMITHRA Maintenance",
                "description": "Bill Payment",
                "handler": function (response) {
                    // AJAX to update bill status in DB
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "update_payment_status.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert("Payment Successful");
                            location.reload(); // Refresh page to show "Paid"
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

<h2>Maintenance Bill</h2>

<?php if ($bill): ?>
    <div id="bill-details">
        <p><strong>Apartment ID:</strong> <?= $apartment_id ?></p>
        <p><strong>Due Date:</strong> <?= $bill['due_date'] ?></p>
        <p><strong>Water Bill:</strong> ₹<?= $bill['water_bill'] ?></p>
        <p><strong>Electricity Bill:</strong> ₹<?= $bill['electricity_bill'] ?></p>
        <p><strong>Maintenance Charge:</strong> ₹<?= $bill['maintenance_charge'] ?></p>
        <p><strong>Total:</strong> ₹<?= $bill['amount'] ?></p>
        <p><strong>Status:</strong> <?= $bill['status'] ?></p>

        <?php if ($bill['status'] == 'Pending'): ?>
            <button onclick="payNow(<?= $bill['maintenance_id'] ?>, <?= $bill['amount'] ?>)">Pay</button>
        <?php endif; ?>

        <button onclick="printBill()">Print</button>
    </div>
<?php else: ?>
    <p>No pending bills found.</p>
<?php endif; ?>

</body>
</html>
