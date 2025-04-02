<?php
// Start session (to track logged-in user)
session_start();

// Include DB connection
include '../db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle Notification Submission (Only for Admins or Security)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_notification'])) {
    if ($role !== 'Admin' && $role !== 'Security') {
        die("Error: Only Admins or Security can send notifications.");
    }
    
    // Validate input
    if (empty($_POST['notification_text'])) {
        die("Error: Notification text cannot be empty.");
    }

    $notification_text = $conn->real_escape_string($_POST['notification_text']);
    
    // Insert notification into DB
    $stmt = $conn->prepare("INSERT INTO Notifications (sender_id, notification_text, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $user_id, $notification_text);
    
    if ($stmt->execute()) {
        $message = "Notification sent successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch Notifications
$sql = "SELECT n.notification_id, n.notification_text, n.created_at, u.username FROM Notifications n 
        JOIN Users u ON n.sender_id = u.user_id ORDER BY n.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notification System</title>
</head>
<body>

<h2>Send a Notification</h2>
<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<?php if ($role === 'Admin' || $role === 'Security') { ?>
<form method="POST">
    <label>Notification:</label>
    <textarea name="notification_text" required></textarea><br>
    <button type="submit" name="submit_notification">Send Notification</button>
</form>
<?php } ?>

<h2>All Notifications</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Sender</th>
        <th>Notification</th>
        <th>Sent On</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['notification_id']; ?></td>
        <td><?= htmlspecialchars($row['username']); ?></td>
        <td><?= htmlspecialchars($row['notification_text']); ?></td>
        <td><?= $row['created_at']; ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>