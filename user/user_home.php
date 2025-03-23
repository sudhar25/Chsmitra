//<?php
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
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome, User!</h1>
    <p>You have access to all society management features.</p>
    <a href="../logout.php">Logout</a>
    <a href="view_notifications.php">View notifications</a>
    <a href="complaints.php">Complaints</a>
    <a href="bill_payments.php">Bill payments</a>
</body>
</html>