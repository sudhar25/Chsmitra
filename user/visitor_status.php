<?php
session_start();
include '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'] ?? 1;

// Get society_id
$stmt = $conn->prepare("SELECT society_id FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result()->fetch_assoc();
$society_id = $user_result['society_id'] ?? null;

// Fetch all visitors requested by members of same society
$stmt = $conn->prepare("SELECT * FROM Visitors WHERE society_id = ?");
$stmt->bind_param("i", $society_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Approval</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
    <a href="../logout.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Logout</a>
        <a href="user_home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Member</a>
        <a href="../home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Home</a>
    </div>
</nav>

<!-- Layout -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div id="menu" class="d-flex flex-column text-white p-3" style="min-width: 200px; background-color: #336699;">
        <a href="temp_bill.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Pay Bill</a>

        <a href="visitor_approval.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Approval Request</a>

        <a href="complaints.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Complaint</a>

        <a href="user_profile.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Profile</a>

        <a href="visitor_status.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Status</a>
    </div>

    <!-- Main Content: Visitor Approval Table -->
    <div class="flex-grow-1 p-4">
        <h2>Visitor Approval Requests</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Visitor Name</th>
                        <th>Contact</th>
                        <th>Purpose</th>
                        <th>Check-Out</th>
                        <th>Security Status</th>
                        <th>Admin Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['visitor_name'] ?></td>
                            <td><?= $row['contact'] ?></td>
                            <td><?= $row['purpose'] ?></td>
                            <td><?= $row['check_out'] ?></td>
                            <td><?= $row['security_approved'] ? "Approved" : "Pending" ?></td>
                            <td><?= $row['admin_approved'] ? "Approved" : "Pending" ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No visitors have requested.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

</body>
</html>
