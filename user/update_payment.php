<?php
include '../db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bill_id = $_POST['bill_id'];
    $razorpay_payment_id = $_POST['razorpay_payment_id'];

    // Just update status for now
    $stmt = $conn->prepare("UPDATE Maintenance SET status = 'Paid' WHERE maintenance_id = ?");
    $stmt->bind_param("i", $bill_id);
    $stmt->execute();

    echo "Payment updated.";
}
?>
