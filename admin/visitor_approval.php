<?php
session_start();
include '../db.php';

// Handle Admin Approval
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_visitor'])) {
    $visitor_id = $_POST['visitor_id'];

    $query = "UPDATE Visitors SET admin_approved = 1 WHERE visitor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $visitor_id);
    if ($stmt->execute()) {
        $message = "Visitor request approved successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch visitors pending admin approval (already approved by security)
$sql = "SELECT * FROM Visitors WHERE security_approved = 1 AND admin_approved = 0 ORDER BY check_in DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve Visitors</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center bg-light px-3 py-2">
    <div class="d-flex align-items-center">
    <img src="../Images/logo.png" alt="Logo" width="50" height="50">
        <div class="ml-3 font-weight-bold">Admin Panel</div>
    </div>
    <div class="d-flex">
        <a href="../logout.php" class="nav-link">Logout</a>
        <a href="about_us.php" class="nav-link">About Us</a>
        <a href="contact_us.php" class="nav-link">Contact Us</a>
    </div>
</nav>

<!-- Layout -->
<div class="layout d-flex">
    <!-- Sidebar Menu -->
    <div id="menu" class="d-flex flex-column bg-secondary text-white p-3" style="min-width: 200px;">
        <a href="maintanance_bill.php" class="text-white py-1">Maintenance Bill</a>
        <a href="manage_apartment.php" class="text-white py-1">Manage Apartment</a>
        <a href="manage_complaint.php" class="text-white py-1">Manage Complaint</a>
        <a href="notification.php" class="text-white py-1">Notification</a>
        <a href="view_bill.php" class="text-white py-1">View Bill</a>
        <a href="register.php" class="text-white py-1">Register</a>
        <a href="visitor_approval.php" class="text-white py-1">Visitor Approval</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2 class="text-center mb-4 text-danger">Admin Approval Panel</h2>
        <?php if (isset($message)) echo "<p class='text-success text-center'>$message</p>"; ?>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>Visitor Name</th>
                    <th>Contact</th>
                    <th>Purpose</th>
                    <th>Check-In</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr class="text-center">
                    <td><?= htmlspecialchars($row['visitor_name']); ?></td>
                    <td><?= htmlspecialchars($row['contact']); ?></td>
                    <td><?= htmlspecialchars($row['purpose']); ?></td>
                    <td><?= $row['check_in']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="visitor_id" value="<?= $row['visitor_id']; ?>">
                            <button type="submit" name="approve_visitor" class="btn btn-success btn-sm">
                                Approve
                            </button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="text-center bg-light py-3 mt-5">
    <p>All rights are reserved by CHSMITHRA</p>
</footer>

</body>
</html>
