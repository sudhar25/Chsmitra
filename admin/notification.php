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
<html>
<head>
    <title>Admin Notification</title>
</head>
<body>

<h2>Notification</h2>
<?php if ($message) echo "<p><strong>$message</strong></p>"; ?>

<h3>Bulk Notification to All Users in a Society</h3>
<form method="POST">
    Society ID: <input type="number" name="society_id" required><br><br>
    Message:<br>
    <textarea name="message" rows="4" cols="50" required></textarea><br><br>
    <button type="submit" name="send_bulk">Send</button>
</form>

<hr>

<h3>Send Notification to Specific User</h3>
<form method="POST">
    Society ID: <input type="number" name="society_id" required><br><br>
    User ID: <input type="number" name="user_id" required><br><br>
    Message:<br>
    <textarea name="message" rows="4" cols="50" required></textarea><br><br>
    <button type="submit" name="send_individual">Send</button>
</form>

</body>
</html>
