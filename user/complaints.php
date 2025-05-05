<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'SocietyManagement';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch complaints with user and apartment details
$sql = "SELECT 
            c.complaint_id, 
            c.complaint_text, 
            c.status, 
            c.created_at,
            c.image_path,
            u.name AS user_name,
            a.apartment_number
        FROM Complaints c
        JOIN Users u ON c.user_id = u.user_id
        JOIN Apartments a ON c.apartment_id = a.apartment_id
        ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaints</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h2>Complaint List</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Apartment</th>
                <th>Text</th>
                <th>Status</th>
                <th>Image</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complaint_id']) ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['apartment_number']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['complaint_text'])) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['image_path']): ?>
                            <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image" width="100">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No complaints found.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
