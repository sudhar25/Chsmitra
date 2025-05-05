<?php
session_start();

//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Security Guard') {
  //  header("Location: login.php");
    //exit();
//}
include '../db.php'; // Adjust the path if needed

// Handle complaint status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Complaints SET status = ? WHERE complaint_id = ?");
    $stmt->bind_param("si", $new_status, $complaint_id);
    $stmt->execute();
    $stmt->close();

    $message = "Status updated successfully!";
}

// Fetch complaints with user info
$sql = "
    SELECT 
        c.complaint_id, c.society_id, c.complaint_text, c.status, c.created_at, 
        c.image_path, c.apartment_id, IFNULL(u.name, 'Unknown') AS user_name 
    FROM Complaints c 
    LEFT JOIN Users u ON c.user_id = u.user_id 
    ORDER BY c.created_at DESC
";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }
    </script>
</head>
<body style="background-color: #f5f9fc;">

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #ADD8E6;">
    <div class="d-flex align-items-center">
        <img src="../Images/logo.png" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem; color: #003366;">☰</div>
    </div>
    <div class="d-flex">
    <a href="../logout.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Logout</a>
        <a href="security_home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Security</a>
        <a href="../home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Home</a>
    </div>
</nav>

<!-- Layout: Sidebar + Main Content -->
<div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar Menu -->
    <div id="menu" class="d-flex flex-column text-white p-3"
         style="min-width: 200px; background-color: #336699; height: 100%;">
        <a href="complaint_management.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Complaint Management</a>

        <a href="guest_approval.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Approval</a>

        <a href="notification_system.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Send Notification</a>

           <a href="view_details.php" class="text-white py-1" 
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Details</a>

        
    
        
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2 class="mb-3">Manage Complaints</h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
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
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
