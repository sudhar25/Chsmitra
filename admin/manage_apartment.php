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
<html>
<head>
    <title>Manage Apartments</title>
</head>
<body>

<h2>Manage Apartments</h2>

<?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

<!-- Add Apartment Form -->
<h3>Add New Apartment</h3>
<form method="POST">
    Society ID: <input type="number" name="society_id" required><br><br>
    Apartment Number: <input type="text" name="apartment_number" required><br><br>
    <button type="submit" name="add_apartment">Add Apartment</button>
</form>

<hr>

<!-- 🖨️ Print Button -->
<button onclick="printTable()">Print</button><br><br>

<!-- View Apartments Table -->
<h3>All Apartments</h3>
<table id="apartmentTable" border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Society ID</th>
        <th>Apartment Number</th>
        <th>Owner Name</th>
        <th>Tenant Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['apartment_id']; ?></td>
        <td><?= $row['society_id']; ?></td>
        <td><?= $row['apartment_number']; ?></td>
        <td><?= $row['owner_name'] ?? 'N/A'; ?></td>
        <td><?= $row['tenant_name'] ?? 'N/A'; ?></td>
        <td>
            <a href="?delete_id=<?= $row['apartment_id']; ?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- 📜 Print Script -->
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
</script>

</body>
</html>
