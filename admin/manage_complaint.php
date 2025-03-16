<?php
// Start session (optional if login is handled)
session_start();

// Include DB connection
include '../db.php';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);

    if ($stmt->execute()) {
        $message = "Status updated successfully!";
    } else {
        $message = "Error updating status: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all complaints with image and user info
$sql = "
    SELECT 
        c.complaint_id, c.society_id, c.complaint_text, c.status, c.created_at, 
        c.image_path, c.apartment_id, u.name AS user_name 
    FROM Complaints c 
    LEFT JOIN Users u ON c.user_id = u.user_id 
    ORDER BY c.complaint_id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Complaints</title>
</head>
<body>

<h2>Complaints</h2>
<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Society ID</th>
        <th>User Name</th>
        <th>Apartment ID</th>
        <th>Complaint</th>
        <th>Image</th>
        <th>Status</th>
        <th>Change Status</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['complaint_id']; ?></td>
        <td><?= $row['society_id']; ?></td>
        <td><?= htmlspecialchars($row['user_name']); ?></td>
        <td><?= $row['apartment_id']; ?></td>
        <td><?= htmlspecialchars($row['complaint_text']); ?></td>
        <td>
            <?php if ($row['image_path']) { ?>
                <img src="../uploads/<?= htmlspecialchars($row['image_path']); ?>" width="100" alt="Complaint Image">
            <?php } else { echo "No Image"; } ?>
        </td>
        <td><strong><?= $row['status']; ?></strong></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="complaint_id" value="<?= $row['complaint_id']; ?>">
                <select name="status">
                    <option value="Open" <?= $row['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
                    <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                </select>
                <button type="submit" name="update_status">Update</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
