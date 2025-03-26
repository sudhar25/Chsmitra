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
<html>
<head>
    <title>Admin - Bills by Society</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h2 { margin-top: 40px; }
        table { border-collapse: collapse; width: 100%; background: white; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #007bff; color: white; }
        button { padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px; }
        button:hover { background: #218838; }
        .paid { color: green; font-weight: bold; }
        .pending { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h1>Admin Dashboard - Bill Payment Status by Society</h1>

<?php if (!empty($societies)): ?>
    <?php foreach($societies as $society_name => $bills): ?>
        <h2>Society: <?= htmlspecialchars($society_name) ?></h2>
        <table>
            <tr>
                <th>Apartment</th>
                <th>Owner</th>
                <th>Bill ID</th>
                <th>Amount (₹)</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
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
                                <button type="submit" name="update_status">Mark as Paid</button>
                            </form>
                        <?php else: ?>
                            <span class="paid">Paid</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    <p>No bills found.</p>
<?php endif; ?>

</body>
</html>
