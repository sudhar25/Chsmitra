<?php
include '../db.php';

// Handle status update
if (isset($_POST['update_status'])) {
    $maintenance_id = $_POST['maintenance_id'];
    $update_sql = "UPDATE Maintenance SET status='Paid' WHERE maintenance_id=$maintenance_id";
    $conn->query($update_sql);
    echo "<script>alert('Status updated to Paid'); window.location.href=window.location.href;</script>";
}

// Fetch all bills with society, apartment, and user info
$sql = "SELECT s.name AS society_name, s.society_id, a.apartment_number, u.name AS owner_name,
               m.maintenance_id, m.amount, m.due_date, m.status
        FROM Maintenance m
        JOIN Apartments a ON m.apartment_id = a.apartment_id
        JOIN Societies s ON m.society_id = s.society_id
        JOIN Users u ON a.owner_id = u.user_id
        ORDER BY s.name, a.apartment_number";
$result = $conn->query($sql);

// Organize data by society
$societies = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $societies[$row['society_name']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bills by Society</title>
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
        <h1 class="text-center mb-4">Admin Dashboard - Bill Payment Status by Society</h1>

        <?php if (!empty($societies)): ?>
            <?php foreach($societies as $society_name => $bills): ?>
                <h2 class="mt-4"><?= htmlspecialchars($society_name) ?></h2>
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Apartment</th>
                            <th>Owner</th>
                            <th>Bill ID</th>
                            <th>Amount (₹)</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bills as $bill): ?>
                            <tr>
                                <td><?= htmlspecialchars($bill['apartment_number']) ?></td>
                                <td><?= htmlspecialchars($bill['owner_name']) ?></td>
                                <td><?= $bill['maintenance_id'] ?></td>
                                <td><?= $bill['amount'] ?></td>
                                <td><?= $bill['due_date'] ?></td>
                                <td class="<?= strtolower($bill['status']) ?>"><?= $bill['status'] ?></td>
                                <td>
                                    <?php if ($bill['status'] == 'Pending'): ?>
                                        <form method="POST">
                                            <input type="hidden" name="maintenance_id" value="<?= $bill['maintenance_id'] ?>">
                                            <button type="submit" name="update_status" class="btn btn-success btn-sm">Mark as Paid</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No bills found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center bg-light py-3 mt-5">
    <p>All rights are reserved by CHSMITHRA</p>
</footer>

</body>
</html>
