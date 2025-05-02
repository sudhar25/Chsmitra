<?php
// Include DB connection
include '../db.php';

// Handle Add Apartment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_apartment'])) {
    $society_id = $_POST['society_id'];
    $apartment_number = $_POST['apartment_number'];

    $stmt = $conn->prepare("INSERT INTO Apartments (society_id, apartment_number) VALUES (?, ?)");
    $stmt->bind_param("is", $society_id, $apartment_number);

    if ($stmt->execute()) {
        $message = " Apartment added successfully!";
    } else {
        $message = " Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Delete Apartment
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM Apartments WHERE apartment_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = " Apartment deleted successfully!";
    } else {
        $message = " Delete failed: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch Apartments
$result = $conn->query("
    SELECT 
        a.apartment_id, 
        a.society_id, 
        a.apartment_number, 
        u1.name AS owner_name, 
        u2.name AS tenant_name
    FROM Apartments a
    LEFT JOIN Users u1 ON a.owner_id = u1.user_id
    LEFT JOIN Users u2 ON a.tenant_id = u2.user_id
    ORDER BY a.apartment_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Apartments</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function printTable() {
            var tableContent = document.getElementById('apartmentTable').outerHTML;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Apartments</title>');
            printWindow.document.write('<style>table, th, td { border:1px solid black; border-collapse:collapse; padding:8px; } th { background-color:#f2f2f2; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h2>Apartment List</h2>');
            printWindow.document.write(tableContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

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

<!-- Layout: Sidebar + Main Content -->
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
        <h2>Manage Apartments</h2>

        <?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

        <!-- Add Apartment Form -->
        <h3>Add New Apartment</h3>
        <form method="POST">
            <div class="form-group">
                <label for="society_id">Society ID:</label>
                <input type="number" class="form-control" id="society_id" name="society_id" required>
            </div>

            <div class="form-group">
                <label for="apartment_number">Apartment Number:</label>
                <input type="text" class="form-control" id="apartment_number" name="apartment_number" required>
            </div>

            <button type="submit" class="btn btn-primary" name="add_apartment">Add Apartment</button>
        </form>

        <hr>

        <!-- Print Button -->
        <button class="btn btn-secondary" onclick="printTable()">Print</button><br><br>

        <!-- View Apartments Table -->
        <h3>All Apartments</h3>
        <table id="apartmentTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Society ID</th>
                    <th>Apartment Number</th>
                    <th>Owner Name</th>
                    <th>Tenant Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['apartment_id']; ?></td>
                        <td><?= $row['society_id']; ?></td>
                        <td><?= $row['apartment_number']; ?></td>
                        <td><?= $row['owner_name'] ?? 'N/A'; ?></td>
                        <td><?= $row['tenant_name'] ?? 'N/A'; ?></td>
                        <td>
                            <a href="?delete_id=<?= $row['apartment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">Delete</a>
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
