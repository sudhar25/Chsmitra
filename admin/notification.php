<?php
include '../db.php';
require '../vendor/autoload.php'; // Only PHPMailer now
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

// Function to send Email
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Update SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com';
        $mail->Password = 'your-email-password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        $mail->setFrom('your-email@example.com', 'Admin');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Bulk Notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_bulk'])) {
    $society_id = $_POST['society_id'];
    $notif_message = $_POST['message'];

    $stmt = $conn->prepare("SELECT user_id, email, phone FROM Users WHERE society_id = ?");
    $stmt->bind_param("i", $society_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt_insert = $conn->prepare("INSERT INTO Notifications (society_id, user_id, message) VALUES (?, ?, ?)");

    while ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $email = $row['email'];

        $stmt_insert->bind_param("iis", $society_id, $user_id, $notif_message);
        $stmt_insert->execute();

        if ($email) sendEmail($email, "New Notification", $notif_message);
    }

    $stmt->close();
    $stmt_insert->close();
    $message = "Notifications sent via Email and Database!";
}

// Individual Notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_individual'])) {
    $society_id = $_POST['society_id'];
    $user_id = $_POST['user_id'];
    $notif_message = $_POST['message'];

    $stmt = $conn->prepare("SELECT email, phone FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $email = $result['email'];

    $stmt_insert = $conn->prepare("INSERT INTO Notifications (society_id, user_id, message) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("iis", $society_id, $user_id, $notif_message);
    $stmt_insert->execute();

    if ($email) sendEmail($email, "New Notification", $notif_message);

    $stmt->close();
    $stmt_insert->close();
    $message = "Notification sent via Email and Database!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Notification</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
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

<!-- Layout Wrapper -->
<div class="layout d-flex">
    <!-- Sidebar -->
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
        <h2>Notification</h2>
        <?php if ($message) echo "<p><strong>$message</strong></p>"; ?>

        <h3>Bulk Notification to All Users in a Society</h3>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="society_id_bulk">Society ID:</label>
                <input type="number" class="form-control" id="society_id_bulk" name="society_id" required>
            </div>
            <div class="form-group">
                <label for="message_bulk">Message:</label>
                <textarea class="form-control" id="message_bulk" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" name="send_bulk" class="btn btn-primary">Send</button>
        </form>

        <hr>

        <h3>Send Notification to Specific User</h3>
        <form method="POST">
            <div class="form-group">
                <label for="society_id_individual">Society ID:</label>
                <input type="number" class="form-control" id="society_id_individual" name="society_id" required>
            </div>
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="number" class="form-control" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="message_individual">Message:</label>
                <textarea class="form-control" id="message_individual" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" name="send_individual" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center py-3 mt-5">
    <p class="mb-0">All rights are reserved by CHSMITHRA</p>
</footer>

</body>
</html>
