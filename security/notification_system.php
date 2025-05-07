<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Security Guard') {
    header("Location: ../login.php");
    exit();
}
include '../db.php';
require '../vendor/autoload.php'; // Only PHPMailer now
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$message = "";

// Function to send Email
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP1_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP1_USERNAME'];
        $mail->Password = $_ENV['SMTP1_PASSWORD'];
        $mail->SMTPSecure = $_ENV['SMTP1_SECURE'];
        $mail->Port = $_ENV['SMTP1_PORT'];
        
        
        $mail->setFrom($_ENV['SMTP1_USERNAME'], $_ENV['SMTP1_FROM_NAME']);
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
    <title>Security Notification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
        <a href="security_home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Security</a>
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
        <a href="complaint_management.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Complaint Management</a>

        <a href="guest_approval.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Approval</a>

        <a href="notification_system.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Notification</a>
         
           <a href="view_details.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Details</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Security Notification</h2>
        <?php //if ($message) echo "<p><strong>$message</strong></p>"; ?>

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
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>


</body>
</html>
