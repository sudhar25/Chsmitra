<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'SocietyManagement';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user_id from session (replace 1 with actual login system fallback if needed)
$user_id = $_SESSION['user_id'] ?? 1;

// Fetch society_id of logged-in user
$stmt = $conn->prepare("SELECT society_id FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$society_id = $user_data['society_id'] ?? null;

// Approve logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['visitor_id'])) {
    $visitor_id = $_POST['visitor_id'];

    if (isset($_POST['approve_security'])) {
        $update = $conn->prepare("UPDATE Visitors SET security_approved = 1, approved_by = ? WHERE visitor_id = ?");
        $update->bind_param("ii", $user_id, $visitor_id);
        $update->execute();
        $update->close();
    }

    if (isset($_POST['approve_admin'])) {
        $update = $conn->prepare("UPDATE Visitors SET admin_approved = 1, approved_by = ? WHERE visitor_id = ?");
        $update->bind_param("ii", $user_id, $visitor_id);
        $update->execute();
        $update->close();
    }
}

// Fetch pending visitor approvals
$visitors = [];
if ($society_id) {
    $stmt = $conn->prepare("SELECT * FROM Visitors WHERE society_id = ? AND (security_approved = 0 OR admin_approved = 0)");
    $stmt->bind_param("i", $society_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $visitors[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Approval</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional external style -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f7ff;
        }
        #menu a {
            color: lightblue;
            text-decoration: none;
        }
        #menu a:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: lightblue;">
    <div class="d-flex align-items-center">
        <img src="../Images/logo.png" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem;">☰</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link" style="color: #003366;">Logout</a>
        <a href="user_dashboard.php" class="nav-link" style="color: #003366;">Dashboard</a>
        <a href="../home.php" class="nav-link" style="color: #003366;">Home</a>
    </div>
</nav>

<!-- Layout -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div id="menu" class="d-flex flex-column text-white p-3" style="min-width: 200px; background-color: #336699;">
        <a href="my_bill.php" class="py-1">My Bill</a>
        <a href="submit_complaint.php" class="py-1">Submit Complaint</a>
        <a href="view_notifications.php" class="py-1">Notifications</a>
        <a href="edit_profile.php" class="py-1">Edit Profile</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2 class="mb-4">Pending Visitor Approvals</h2>

        <?php if (count($visitors) > 0): ?>
            <?php foreach ($visitors as $visitor): ?>
                <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                    <p><strong>Name:</strong> <?= htmlspecialchars($visitor['visitor_name']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($visitor['contact']) ?></p>
                    <p><strong>Purpose:</strong> <?= htmlspecialchars($visitor['purpose']) ?></p>
                    <p><strong>Check-In:</strong> <?= htmlspecialchars($visitor['check_in']) ?></p>
                    <form method="POST" class="mt-2">
                        <input type="hidden" name="visitor_id" value="<?= $visitor['visitor_id'] ?>">
                        <?php if (!$visitor['security_approved']): ?>
                            <button type="submit" name="approve_security" class="btn btn-warning mr-2">Approve as Security</button>
                        <?php endif; ?>
                        <?php if (!$visitor['admin_approved']): ?>
                            <button type="submit" name="approve_admin" class="btn btn-success">Approve as Admin</button>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">No pending visitor approvals.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

<script>
    function toggleMenu() {
        const menu = document.getElementById('menu');
        menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    }
</script>

</body>
</html>
