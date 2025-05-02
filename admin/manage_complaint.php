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
        c.image_path, c.apartment_id, IFNULL(u.name, 'Unknown User') AS user_name 
    FROM Complaints c 
    LEFT JOIN Users u ON c.user_id = u.user_id 
    ORDER BY c.complaint_id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Complaints</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>
</head>
<body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center bg-light px-3 py-2">
    <div class="d-flex align-items-center">
    <img src="../Images/logo.png" alt="Logo" width="50" height="50">
        <div class="hamburger ml-3" onclick="toggleMenu()">☰</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link">Logout</a>
        <a href="about_us.php" class="nav-link">About Us</a>
        <a href="contact_us.php" class="nav-link">Contact Us</a>
    </div>
</nav>

<!-- Layout Wrapper -->
<div class="layout d-flex">
    <!-- Sidebar -->
    <div id="menu" class="d-flex flex-column bg-secondary text-white p-3" style="min-width: 200px;">
        <a href="maintenance_bill.php" class="text-white py-1">Maintenance Bill</a>
        <a href="manage_apartment.php" class="text-white py-1">Manage Apartment</a>
        <a href="manage_complaint.php" class="text-white py-1">Manage Complaint</a>
        <a href="notification.php" class="text-white py-1">Notification</a>
        <a href="view_bill.php" class="text-white py-1">View Bill</a>
        <a href="register.php" class="text-white py-1">Register</a>
        <a href="visitor_approval.php" class="text-white py-1">Visitor Approval</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Complaints</h2>
        <?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
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
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['complaint_id']; ?></td>
                        <td><?= $row['society_id']; ?></td>
                        <td><?= htmlspecialchars($row['user_name']); ?></td>
                        <td><?= $row['apartment_id']; ?></td>
                        <td><?= htmlspecialchars($row['complaint_text']); ?></td>
                        <td>
                            <?php if ($row['image_path']) { ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image_path']); ?>" width="100" alt="Complaint Image" class="img-fluid">
                            <?php } else { echo "No Image"; } ?>
                        </td>
                        <td><strong><?= $row['status']; ?></strong></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="complaint_id" value="<?= $row['complaint_id']; ?>">
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="Open" <?= $row['status'] == 'Open' ? 'selected' : '' ?>>Open</option>
                                        <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center py-3 mt-5">
    <p class="mb-0">All rights are reserved by CHSMITHRA</p>
</footer>

</body>
</html>
