<?php
session_start();
include '../db.php';

// Ensure the user is Security
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Security') {
  //  die("Error: Unauthorized access.");
//}

// Fetch visitors pending security approval
$sql = "SELECT * FROM Visitors WHERE security_approved = 0 ORDER BY check_in DESC";
$result = $conn->query($sql);

// Handle visitor approval by security
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_visitor'])) {
    $visitor_id = $_POST['visitor_id'];

    $stmt = $conn->prepare("UPDATE Visitors SET security_approved = 1 WHERE visitor_id = ?");
    $stmt->bind_param("i", $visitor_id);
    if ($stmt->execute()) {
        $message = "Visitor request approved successfully!";
    } else {
        $message = "Error updating status: " . $stmt->error;
    }
    $stmt->close();
    header("Location: guest_approval.php"); // Refresh page
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Approval - Security</title>
</head>
<body>
    <h2>Security Approval for Visitors</h2>
    <?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Visitor Name</th>
            <th>Contact</th>
            <th>Purpose</th>
            <th>Check-In</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['visitor_id']; ?></td>
            <td><?= htmlspecialchars($row['visitor_name']); ?></td>
            <td><?= htmlspecialchars($row['contact']); ?></td>
            <td><?= htmlspecialchars($row['purpose']); ?></td>
            <td><?= $row['check_in']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="visitor_id" value="<?= $row['visitor_id']; ?>">
                    <button type="submit" name="approve_visitor">Approve</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
