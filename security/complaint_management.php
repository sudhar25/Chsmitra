<?php
// Start session (to track logged-in user)
session_start();

// Debugging: Check if session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    die("Error: Unauthorized access. Session variables not set.");
}

// Ensure only Security personnel can access
if ($_SESSION['role'] !== 'Security') {
    die("Error: Unauthorized access. You do not have permission.");
}

// Include DB connection
include '../db.php';

$user_id = $_SESSION['user_id'];
$society_id = $_SESSION['society_id'] ?? null; // Ensure it's set

// Fetch All Complaints
$sql = "SELECT c.complaint_id, c.complaint_text, c.image_path, c.status, c.created_at, 
               u.username, u.apartment_id 
        FROM Complaints c 
        JOIN Users u ON c.user_id = u.user_id 
        WHERE c.society_id = ?
        ORDER BY c.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $society_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle Complaint Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);
    
    if ($stmt->execute()) {
        $message = "Complaint status updated successfully!";
    } else {
        $message = "Error updating status: " . $stmt->error;
    }

    // Refresh page to reflect changes
    header("Location: security_complaint_mgmt.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaint Management</title>
</head>
<body>

<h2>Manage Complaints</h2>
<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Apartment</th>
        <th>Complaint</th>
        <th>Image</th>
        <th>Status</th>
        <th>Submitted On</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['complaint_id']; ?></td>
        <td><?= htmlspecialchars($row['username']); ?></td>
        <td><?= htmlspecialchars($row['apartment_id']); ?></td>
        <td><?= htmlspecialchars($row['complaint_text']); ?></td>
        <td>
            <?php if ($row['image_path']) { ?>
                <img src="../uploads/<?= htmlspecialchars($row['image_path']); ?>" width="100" alt="Complaint Image">
            <?php } else { echo "No Image"; } ?>
        </td>
        <td><strong><?= $row['status']; ?></strong></td>
        <td><?= $row['created_at']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="complaint_id" value="<?= $row['complaint_id']; ?>">
                <select name="status">
                    <option value="Open" <?= $row['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                    <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                </select>
                <button type="submit" name="update_status">Update</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
