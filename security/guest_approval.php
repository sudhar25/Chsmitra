<?php
// Start session (to track logged-in user)
session_start();

// Include DB connection
include '../db.php';

// Ensure user is logged in and has the Security role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Security') {
    die("Error: Unauthorized access.");
}

// Fetch pending guest approvals
$sql = "SELECT guest_id, visitor_name, apartment_id, visit_reason, status, created_at FROM Guest_Entries WHERE status = 'Pending' ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle guest approval or rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $guest_id = $_POST['guest_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Guest_Entries SET status = ? WHERE guest_id = ?");
    $stmt->bind_param("si", $new_status, $guest_id);
    if ($stmt->execute()) {
        $message = "Guest status updated successfully!";
    } else {
        $message = "Error updating status: " . $stmt->error;
    }
    $stmt->close();
    // Refresh page to show updated status
    header("Location: guest_approval.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Approval</title>
</head>
<body>

<h2>Approve or Reject Guests</h2>
<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Visitor Name</th>
        <th>Apartment</th>
        <th>Visit Reason</th>
        <th>Status</th>
        <th>Requested On</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['guest_id']; ?></td>
        <td><?= htmlspecialchars($row['visitor_name']); ?></td>
        <td><?= htmlspecialchars($row['apartment_id']); ?></td>
        <td><?= htmlspecialchars($row['visit_reason']); ?></td>
        <td><strong><?= $row['status']; ?></strong></td>
        <td><?= $row['created_at']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="guest_id" value="<?= $row['guest_id']; ?>">
                <select name="status">
                    <option value="Approved">Approve</option>
                    <option value="Rejected">Reject</option>
                </select>
                <button type="submit" name="update_status">Update</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
