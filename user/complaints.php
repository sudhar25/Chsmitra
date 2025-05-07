<?php
session_start();

// Check if the user is logged in and has the 'Member' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member') {
    header("Location: ../login.php");
    exit();
}

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_complaint'])) {

    // Get input data
    $complaint_text = mysqli_real_escape_string($conn, $_POST['complaint_text']);
    $apartment_id = $_POST['apartment_id'];
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in and their ID is known

    // Get society_id from the apartment
    $stmt = $conn->prepare("SELECT society_id FROM Apartments WHERE apartment_id = ?");
    $stmt->bind_param("i", $apartment_id);
    $stmt->execute();
    $stmt->bind_result($society_id);
    $stmt->fetch();
    $stmt->close();

    // Image handling
    $image_path = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Check file type and size
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = $_FILES['image']['type'];
        $max_size = 2 * 1024 * 1024; // 2 MB max size

        if (in_array($file_type, $allowed_types) && $_FILES['image']['size'] <= $max_size) {
            // Upload image
            $target_dir = "../uploads/"; // Directory to store images
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $message = "Sorry, there was an error uploading your image.";
            }
        } else {
            $message = "Invalid file type or file size exceeds the limit.";
        }
    }

    // Insert complaint into database
    $stmt = $conn->prepare("INSERT INTO Complaints (society_id, user_id, apartment_id, complaint_text, status, image_path) 
                            VALUES (?, ?, ?, ?, 'Open', ?)");
    $stmt->bind_param("iiiss", $society_id, $user_id, $apartment_id, $complaint_text, $image_path);

    if ($stmt->execute()) {
        $message = "Complaint submitted successfully!";
    } else {
        $message = "Error submitting complaint: " . $stmt->error;
    }

    $stmt->close();
    echo "<div class='message'>$message</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Complaint</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Navbar -->
<nav class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: lightblue;">
    <div class="d-flex align-items-center">
        <img src="../Images/logo.png" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div class="hamburger ml-3" onclick="toggleMenu()" style="cursor:pointer; font-size: 1.5rem;">☰</div>
    </div>
    <div class="d-flex">
    <a href="../logout.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Logout</a>
        <a href="user_home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Member</a>
        <a href="../home.php" class="nav-link"
           style="color: #003366; transition: 0.3s;"
           onmouseover="this.style.color='black'; this.style.transform='scale(1.1)'"
           onmouseout="this.style.color='#003366'; this.style.transform='scale(1)'">Home</a>
    </div>
</nav>

<!-- Layout -->
<div class="layout d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div id="menu" class="d-flex flex-column text-white p-3" style="min-width: 200px; background-color: #336699;">
        <a href="bill_payments.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Pay Bill</a>

        <a href="visitor_approval.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Approval Request</a>

        <a href="complaints.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Complaint</a>

        <a href="user_profile.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s;"
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">View Profile</a>

        <a href="visitor_status.php" class="text-white py-1"
           style="color: lightblue; text-decoration: none; border-radius: 5px; padding: 8px; transition: 0.3s; "
           onmouseover="this.style.backgroundColor='#003366'; this.style.color='white'; this.style.transform='scale(1.05)'; this.style.boxShadow='0 0 8px rgba(0, 51, 102, 0.5)'"
           onmouseout="this.style.backgroundColor='transparent'; this.style.color='lightblue'; this.style.transform='scale(1)'; this.style.boxShadow='none'">Visitor Status</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <h2>Submit a Complaint</h2>

        <!-- Complaint Form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="apartment_id">Apartment ID</label>
                <input type="number" class="form-control" id="apartment_id" name="apartment_id" required>
            </div>
            <div class="form-group">
                <label for="complaint_text">Complaint Description</label>
                <textarea class="form-control" id="complaint_text" name="complaint_text" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload Image (Optional)</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg, image/png">
            </div>
            <button type="submit" name="submit_complaint" class="btn btn-primary">Submit Complaint</button>
        </form>

        <!-- Message Display -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info mt-3"><?= $message ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center" style="background-color: #ADD8E6; padding: 10px; font-size: 0.85rem;">
    <p style="margin: 0;">© 2025 CHSMITRA. All rights reserved.</p>
</footer>

</body>
</html>

