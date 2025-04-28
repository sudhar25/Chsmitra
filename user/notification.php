<?php
include '../db.php';
require '../vendor/autoload.php'; // For PHPMailer
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

// User sending notification to Admin and Security
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_to_admin_security'])) {
    $user_id = $_POST['user_id']; // Sender's ID (User)
    $notif_message = $_POST['message'];

    // Find society_id of the user and user name
    $stmt = $conn->prepare("SELECT society_id, name FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result()->fetch_assoc();
    $society_id = $user_result['society_id'];
    $sender_name = $user_result['name'];
    $stmt->close();

    if (!$society_id) {
        $message = "Invalid user or society not found.";
    } else {
        // Fetch Admin and Security Guard users in the same society
        $stmt2 = $conn->prepare("SELECT user_id, email FROM Users WHERE society_id = ? AND role IN ('Admin', 'Security Guard')");
        $stmt2->bind_param("i", $society_id);
        $stmt2->execute();
        $admin_sec_result = $stmt2->get_result();

        $stmt_insert = $conn->prepare("INSERT INTO Notifications (society_id, user_id, message) VALUES (?, ?, ?)");

        while ($row = $admin_sec_result->fetch_assoc()) {
            $admin_sec_user_id = $row['user_id'];
            $email = $row['email'];

            // Prepare full message with sender's name
            $full_message = "New Notification from: " . $sender_name . "\n\nMessage:\n" . $notif_message;

            // Insert into Notifications table
            $stmt_insert->bind_param("iis", $society_id, $admin_sec_user_id, $notif_message);
            $stmt_insert->execute();

            // Send Email
            if ($email) sendEmail($email, "New Message from User", $full_message);
        }

        $stmt2->close();
        $stmt_insert->close();

        $message = "Notification successfully sent to Admin and Security Guard!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Notification to Admin and Security</title>
</head>
<body>

<h2>Send Notification</h2>
<?php if ($message) echo "<p><strong>$message</strong></p>"; ?>

<h3>Send Notification to Admin and Security Guard</h3>
<form method="POST">
    Your User ID: <input type="number" name="user_id" required><br><br>
    Message:<br>
    <textarea name="message" rows="4" cols="50" required></textarea><br><br>
    <button type="submit" name="send_to_admin_security">Send</button>
</form>

</body>
</html>
