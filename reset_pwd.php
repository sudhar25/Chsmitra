<?php
session_start();
include 'db.php';

$successMsg = "";
$errorMsg = "";

// Handle Password Reset
if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $role = $_POST['role'];
    $society_id = intval($_POST['society_id']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $errorMsg = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Verify user details
        $sql = "SELECT * FROM Users WHERE email = ? AND role = ? AND society_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $role, $society_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 1) {
            $user = $res->fetch_assoc();
            $sql_update = "UPDATE Users SET password_hash = ? WHERE user_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $hashed_password, $user['user_id']);
            
            if ($stmt_update->execute()) {
                $successMsg = "Password reset successful!";
            } else {
                $errorMsg = "Error updating password.";
            }
        } else {
            $errorMsg = "No user found with the provided details.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 30px; }
        .form-box { background: #fff; padding: 20px; border-radius: 10px; width: 350px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px; width: 100%; background: #007bff; color: white; border: none; border-radius: 5px; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-top: 10px; }
        .success { color: green; margin-top: 10px; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Reset Password</h2>
    <?php if ($errorMsg) echo "<p class='error'>$errorMsg</p>"; ?>
    <?php if ($successMsg) echo "<p class='success'>$successMsg</p>"; ?>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Role:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Member">Member</option>
            <option value="Security Guard">Security Guard</option>
        </select><br>

        <label>Society ID:</label>
        <input type="number" name="society_id" required><br>

        <label>New Password:</label>
        <input type="password" name="new_password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <button type="submit" name="reset">Reset Password</button>
    </form>
</div>

</body>
</html>
