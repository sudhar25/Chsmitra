<?php
// Start session (to track logged-in user)
session_start();

// Include DB connection
include '../db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: Please log in to submit complaints.");
}

$user_id = $_SESSION['user_id'];
$society_id = $_SESSION['society_id']; // Assuming this is stored in session

// Handle Complaint Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_complaint'])) {
    $complaint_text = $_POST['complaint_text'];
    $apartment_id = $_POST['apartment_id'];

    // File Upload Handling
    $image_path = null;
    if (!empty($_FILES['complaint_image']['name'])) {
        $target_dir = "../uploads/";
        $image_path = basename($_FILES["complaint_image"]["name"]);
        $target_file = $target_dir . $image_path;
        move_uploaded_file($_FILES["complaint_image"]["tmp_name"], $target_file);
    }

    // Insert complaint into DB
    $stmt = $conn->prepare("INSERT INTO Complaints (user_id, society_id, apartment_id, complaint_text, image_path, status) VALUES (?, ?, ?, ?, ?, 'Open')");
    $stmt->bind_param("iisss", $user_id, $society_id, $apartment_id, $complaint_text, $image_path);
    
    if ($stmt->execute()) {
        $message = "Complaint submitted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch User's Complaints
$sql = "SELECT complaint_id, complaint_text, status, image_path, created_at FROM Complaints WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$complaints = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Complaints</title>
</head>
<body>

<h2>Submit a Complaint</h2>
<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Apartment ID:</label>
    <input type="text" name="apartment_id" required><br>

    <label>Complaint:</label>
    <textarea name="complaint_text" required></textarea><br>

    <label>Upload Image (Optional):</label>
    <input type="file" name="complaint_image"><br>

    <button type="submit" name="submit_complaint">Submit</button>
</form>

<h2>My Complaints</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Complaint</th>
        <th>Image</th>
        <th>Status</th>
        <th>Submitted On</th>
    </tr>

    <?php while ($row = $complaints->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['complaint_id']; ?></td>
        <td><?= htmlspecialchars($row['complaint_text']); ?></td>
        <td>
            <?php if ($row['image_path']) { ?>
                <img src="../uploads/<?= htmlspecialchars($row['image_path']); ?>" width="100" alt="Complaint Image">
            <?php } else { echo "No Image"; } ?>
        </td>
        <td><strong><?= $row['status']; ?></strong></td>
        <td><?= $row['created_at']; ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
