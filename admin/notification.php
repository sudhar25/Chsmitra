<?php
include '../db.php';

$message = "";

// Handle Bulk Notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_bulk'])) {
    $society_id = $_POST['society_id'];
    $notif_message = $_POST['message'];

    $stmt = $conn->prepare("SELECT user_id FROM Users WHERE society_id = ?");
    $stmt->bind_param("i", $society_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt_insert = $conn->prepare("INSERT INTO Notifications (society_id, user_id, message) VALUES (?, ?, ?)");

    while ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
        $stmt_insert->bind_param("iis", $society_id, $user_id, $notif_message);
        $stmt_insert->execute();
    }

    $stmt->close();
    $stmt_insert->close();

    $message = "notifications sent to users!";
}

// Handle Individual Notification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_individual'])) {
    $society_id = $_POST['society_id'];
    $user_id = $_POST['user_id'];
    $notif_message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO Notifications (society_id, user_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $society_id, $user_id, $notif_message);
    $stmt->execute();
    $stmt->close();

    $message = "Notification sent to user!";
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

<!-- 🔸 Bulk Notification Form -->
<h3>Bulk Notification to All Users in a Society</h3>
<form method="POST">
    Society ID: <input type="number" name="society_id" required><br><br>
    Message:<br>
    <textarea name="message" rows="4" cols="50" required></textarea><br><br>
    <button type="submit" name="send_bulk">Send</button>
</form>

<hr>

<!-- 🔸 Individual Notification Form -->
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
