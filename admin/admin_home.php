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
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, Admin!</h1>
    <p>You have access to all society management features.</p>
    <a href="../logout.php">Logout</a>
    <a href="register.php">Register</a>
    <a href="manage_apartment.php">Apartment</a>
    <a href="manage_complaint.php">Complaint</a>
    <a href="notification.php">Notification</a>
</body>
</html>