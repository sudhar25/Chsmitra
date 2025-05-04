<?php
session_start();

//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member') {
  //  header("Location: login.php");
    //exit();
//}
include '../db.php';

// Handle new visitor request submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $visitor_name = mysqli_real_escape_string($conn, $_POST['visitor_name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);

    $sql = "INSERT INTO Visitors (society_id, visitor_name, contact, purpose, security_approved, admin_approved, check_out) 
            VALUES ('$society_id', '$visitor_name', '$contact', '$purpose', 0, 0, NULL)";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Visitor request submitted successfully!'); window.location.href='visitor_requests.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle visitor check-out update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_out'])) {
    $visitor_id = $_POST['visitor_id'];
    $check_out_time = date("Y-m-d H:i:s");

    $sql = "UPDATE Visitors SET check_out = '$check_out_time' WHERE visitor_id = '$visitor_id' AND check_out IS NULL";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Visitor checked out successfully!'); window.location.href='visitor_requests.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch visitor requests made by the logged-in user
$visitor_requests = mysqli_query($conn, "SELECT * FROM Visitors WHERE society_id='$society_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Request</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-blue-600">Visitor Request Form</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold">Visitor Name</label>
                <input type="text" name="visitor_name" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold">Contact</label>
                <input type="text" name="contact" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold">Purpose</label>
                <textarea name="purpose" required class="w-full p-2 border rounded"></textarea>
            </div>
            <button type="submit" name="submit_request" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit Request
            </button>
        </form>
    </div>

    <!-- Visitor Request Status Table -->
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-green-600">Your Visitor Requests</h2>
        <table class="w-full border-collapse border border-gray-300">
            <tr class="bg-gray-200">
                <th class="border p-2">Visitor Name</th>
                <th class="border p-2">Contact</th>
                <th class="border p-2">Purpose</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Check-Out</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($visitor_requests)) { 
                // Determine the status
                if ($row['admin_approved'] == 1) {
                    $status = "<span class='text-green-500 font-semibold'>Approved</span>";
                } elseif ($row['security_approved'] == 1) {
                    $status = "<span class='text-yellow-500 font-semibold'>Pending Admin Approval</span>";
                } else {
                    $status = "<span class='text-red-500 font-semibold'>Pending Security Approval</span>";
                }
            ?>
                <tr class="text-center">
                    <td class="border p-2"><?php echo htmlspecialchars($row['visitor_name']); ?></td>
                    <td class="border p-2"><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td class="border p-2"><?php echo htmlspecialchars($row['purpose']); ?></td>
                    <td class="border p-2"><?php echo $status; ?></td>
                    <td class="border p-2">
                        <?php if ($row['check_out'] == NULL && $row['admin_approved'] == 1) { ?>
                            <form method="POST">
                                <input type="hidden" name="visitor_id" value="<?php echo $row['visitor_id']; ?>">
                                <button type="submit" name="check_out" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-700">Check Out</button>
                            </form>
                        <?php } else {
                            echo $row['check_out'] ? $row['check_out'] : "-";
                        } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
