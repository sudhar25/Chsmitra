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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-red-600">Admin Approval Panel</h2>
        <?php if (isset($message)) echo "<p class='text-green-600'>$message</p>"; ?>

        <table class="w-full border-collapse border border-gray-300">
            <tr class="bg-gray-200">
                <th class="border p-2">Visitor Name</th>
                <th class="border p-2">Contact</th>
                <th class="border p-2">Purpose</th>
                <th class="border p-2">Check-In</th>
                <th class="border p-2">Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr class="text-center">
                <td class="border p-2"><?= htmlspecialchars($row['visitor_name']); ?></td>
                <td class="border p-2"><?= htmlspecialchars($row['contact']); ?></td>
                <td class="border p-2"><?= htmlspecialchars($row['purpose']); ?></td>
                <td class="border p-2"><?= $row['check_in']; ?></td>
                <td class="border p-2">
                    <form method="POST">
                        <input type="hidden" name="visitor_id" value="<?= $row['visitor_id']; ?>">
                        <button type="submit" name="approve_visitor" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">
                            Approve
                        </button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
