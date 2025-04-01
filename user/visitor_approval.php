<?php
session_start();
include '../db.php'; // Include DB connection

$user_id = $_SESSION['user_id']; // Logged-in user
$society_id = $_SESSION['society_id']; // User's society

// Handle new visitor request submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitor_name = $_POST['visitor_name'];
    $contact = $_POST['contact'];
    $purpose = $_POST['purpose'];

    $sql = "INSERT INTO Visitors (society_id, visitor_name, contact, purpose, approved_by) 
            VALUES ('$society_id', '$visitor_name', '$contact', '$purpose', NULL)";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Visitor request submitted successfully!'); window.location.href='visitor_requests.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch the visitor requests made by the logged-in user
$visitor_requests = mysqli_query($conn, "SELECT * FROM Visitors WHERE society_id='$society_id' AND visitor_name IN 
    (SELECT visitor_name FROM Visitors WHERE approved_by IS NULL OR approved_by IS NOT NULL)");
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
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
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
            </tr>
            <?php while ($row = mysqli_fetch_assoc($visitor_requests)) { 
                // Determine the status
                if ($row['approved_by'] === NULL) {
                    $status = "<span class='text-yellow-500 font-semibold'>Pending</span>";
                } else {
                    $status = "<span class='text-green-500 font-semibold'>Approved</span>";
                }
            ?>
                <tr class="text-center">
                    <td class="border p-2"><?php echo $row['visitor_name']; ?></td>
                    <td class="border p-2"><?php echo $row['contact']; ?></td>
                    <td class="border p-2"><?php echo $row['purpose']; ?></td>
                    <td class="border p-2"><?php echo $status; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
