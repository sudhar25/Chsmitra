<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
include '../db.php';

$societyDetails = null;
$admins = $members = $guards = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $society_name = trim($_POST['society_name']);

    // Fetch Society Info
    $societySql = "SELECT * FROM Societies WHERE name = ?";
    $stmt = $conn->prepare($societySql);
    $stmt->bind_param("s", $society_name);
    $stmt->execute();
    $societyResult = $stmt->get_result();

    if ($societyResult->num_rows > 0) {
        $societyDetails = $societyResult->fetch_assoc();
        $society_id = $societyDetails['society_id'];

        // Fetch Admins
        $stmt = $conn->prepare("SELECT * FROM Users WHERE society_id = ? AND role = 'Admin'");
        $stmt->bind_param("i", $society_id);
        $stmt->execute();
        $admins = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Fetch Members
        $stmt = $conn->prepare("SELECT * FROM Users WHERE society_id = ? AND role = 'Member'");
        $stmt->bind_param("i", $society_id);
        $stmt->execute();
        $members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Fetch Security Guards
        $stmt = $conn->prepare("SELECT * FROM Users WHERE society_id = ? AND role = 'Security Guard'");
        $stmt->bind_param("i", $society_id);
        $stmt->execute();
        $guards = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $error = "No society found with that name.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
    <title>View Society Info</title>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('sidebar');
            menu.classList.toggle('d-none');
        }
    </script>
</head>
<body>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: lightblue;">
    <div class="d-flex align-items-center">
        <img src="../Images/logo.png" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem;">☰</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link" style="color: #003366; transition: 0.3s;" onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'" onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Logout</a>
        <a href="security_home.php" class="nav-link" style="color: #003366; transition: 0.3s;" onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'" onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Security</a>
        <a href="../home.php" class="nav-link" style="color: #003366; transition: 0.3s;" onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'" onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Home</a>
    </div>
</nav>

<!-- Layout: Sidebar + Main Content -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar Menu -->
    <div id="sidebar" class="d-flex flex-column text-white p-3" style="min-width: 200px; background-color: #336699;">
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
    <div class="col-md-9 p-4">
        <h2 class="mb-4">View Society Details</h2>
        <form method="post" class="form-inline mb-4">
            <label for="society_name" class="mr-2">Society Name:</label>
            <input type="text" name="society_name" id="society_name" class="form-control mr-2" required>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif ($societyDetails): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($societyDetails['name']) ?></h4>
                    <p><strong>Address:</strong> <?= htmlspecialchars($societyDetails['address']) ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <h5>Admin(s)</h5>
                    <?php if (count($admins) > 0): ?>
                        <ul class="list-group">
                            <?php foreach ($admins as $admin): ?>
                                <li class="list-group-item"><?= $admin['name'] ?> (<?= $admin['email'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No Admins found.</p>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <h5>Members</h5>
                    <?php if (count($members) > 0): ?>
                        <ul class="list-group">
                            <?php foreach ($members as $member): ?>
                                <li class="list-group-item"><?= $member['name'] ?> (<?= $member['email'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No Members found.</p>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <h5>Security Guard(s)</h5>
                    <?php if (count($guards) > 0): ?>
                        <ul class="list-group">
                            <?php foreach ($guards as $guard): ?>
                                <li class="list-group-item"><?= $guard['name'] ?> (<?= $guard['email'] ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No Guards found.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; margin-top: 20px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
