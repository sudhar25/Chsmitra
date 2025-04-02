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
    <title>Security Dashboard</title>
</head>
<body>
    <h1>Welcome, Security!</h1>
    <p>You have access to all society management features.</p>
    <a href="../logout.php">Logout</a>
    <a href="guest_approval.php">Guest Approval</a>
    <a href="notification_system.php">Notification</a>
    <a href="complaint_management.php">Complaint</a>
</body>
</html>