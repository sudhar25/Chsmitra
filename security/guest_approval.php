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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Approval - Security</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <a href="security_home.php" class="nav-link text-dark" style="font-size: 1rem; transition: 0.3s;">Home</a>
        <a href="../logout.php" class="nav-link text-dark" style="font-size: 1rem; transition: 0.3s;">Logout</a>
    </div>
</nav>

<!-- Layout: Sidebar + Main Content -->
<div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar Menu -->
    <div id="menu" class="d-flex flex-column text-white p-3" style="min-width: 200px; background-color: #336699; height: 100%; display: none;">
        <a href="manage_complaint.php" class="text-white py-1" style="color: lightblue; text-decoration: none; padding: 8px; transition: 0.3s;">
            Manage Complaints
        </a>
        <a href="security_report.php" class="text-white py-1" style="color: lightblue; text-decoration: none; padding: 8px; transition: 0.3s;">
            View Reports
        </a>
        <a href="visitor_approval.php" class="text-white py-1" style="color: lightblue; text-decoration: none; padding: 8px; transition: 0.3s;">
            Visitor Approval
        </a>
        <a href="security_settings.php" class="text-white py-1" style="color: lightblue; text-decoration: none; padding: 8px; transition: 0.3s;">
            Security Settings
        </a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h3 class="mb-3">Security Approval for Visitors</h3>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Visitor Name</th>
                    <th>Contact</th>
                    <th>Purpose</th>
                    <th>Check-In</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
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
                            <button type="submit" name="approve_visitor" class="btn btn-success btn-sm">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5 py-3" style="background-color: #ADD8E6;">
    <small>© 2025 CHSMITRA Security Panel</small>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
