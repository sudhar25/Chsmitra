<?php
include '../db.php';
// Handle status update
if (isset($_POST['update_status'])) {
    $maintenance_id = $_POST['maintenance_id'];
    $update_sql = "UPDATE Maintenance SET status='Paid' WHERE maintenance_id=$maintenance_id";
    $conn->query($update_sql);
    echo "<script>alert('Status updated to Paid');</script>";
}

// Fetch all bills with apartment and user info
$sql = "SELECT m.*, a.apartment_number, u.name AS owner_name
        FROM Maintenance m
        JOIN Apartments a ON m.apartment_id = a.apartment_id
        JOIN Users u ON a.owner_id = u.user_id
        ORDER BY m.due_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Bills Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        table { border-collapse: collapse; width: 100%; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #007bff; color: white; }
        button { padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; }
        button:hover { background: #218838; }
    </style>
</head>
<body>

<h2>All Bills - Admin Dashboard</h2>
<table>
    <tr>
        <th>Bill ID</th>
        <th>Society ID</th>
        <th>Apartment</th>
        <th>Owner</th>
        <th>Water (₹)</th>
        <th>Electricity (₹)</th>
        <th>Maintenance (₹)</th>
        <th>Total (₹)</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['maintenance_id'] ?></td>
                <td><?= $row['society_id'] ?></td>
                <td><?= $row['apartment_number'] ?></td>
                <td><?= $row['owner_name'] ?></td>
                <td><?= $row['water_bill'] ?></td>
                <td><?= $row['electricity_bill'] ?></td>
                <td><?= $row['maintenance_charge'] ?></td>
                <td><?= $row['amount'] ?></td>
                <td><?= $row['due_date'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <?php if ($row['status'] == 'Pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="maintenance_id" value="<?= $row['maintenance_id'] ?>">
                            <button type="submit" name="update_status">Mark as Paid</button>
                        </form>
                    <?php else: ?>
                        <span style="color: green;">Paid</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="11">No bills found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
