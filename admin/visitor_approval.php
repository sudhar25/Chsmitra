<?php
session_start();
include '../db.php';

// Handle Security & Admin Approval
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_visitor'])) {
    $visitor_id = $_POST['visitor_id'];
    $approver = $_POST['approver']; // 'security' or 'admin'

    if ($approver == 'security') {
        $query = "UPDATE Visitors SET security_approved = 1 WHERE visitor_id = ?";
    } elseif ($approver == 'admin') {
        $query = "UPDATE Visitors SET admin_approved = 1 WHERE visitor_id = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $visitor_id);
    if ($stmt->execute()) {
        $message = "Approval updated successfully!";
    } else {
        $message = "Error updating approval: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch visitors pending approval
$sql = "SELECT * FROM Visitors WHERE security_approved = 1 AND admin_approved = 0 ORDER BY check_in DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Approve Visitors</title>
</head>
<body>

<h2>Admin Approval</h2>
<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Visitor Name</th>
        <th>Contact</th>
        <th>Purpose</th>
        <th>Check-In</th>
        <th>Check-Out</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['visitor_id']; ?></td>
        <td><?= htmlspecialchars($row['visitor_name']); ?></td>
        <td><?= htmlspecialchars($row['contact']); ?></td>
        <td><?= htmlspecialchars($row['purpose']); ?></td>
        <td><?= $row['check_in']; ?></td>
        <td><?= $row['check_out']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="visitor_id" value="<?= $row['visitor_id']; ?>">
                <select name="approver">
                    <option value="security">Security Approval</option>
                    <option value="admin">Admin Approval</option>
                </select>
                <button type="submit" name="approve_visitor">Approve</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
