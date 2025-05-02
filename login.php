<?php
session_start();
include 'db.php';

$successMsg = "";
$errorMsg = "";

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password_input = $_POST['password'];
    $society_id = intval($_POST['society_id']);

    $sql = "SELECT * FROM Users WHERE email = ? AND role = ? AND society_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $email, $role, $society_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password_input, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['society_id'] = $user['society_id'];

            // Redirect based on the role
            if ($user['role'] == 'Admin') {
                header("Location: admin_home.php");
            } elseif ($user['role'] == 'Member') {
                header("Location: user_home.php");
            } elseif ($user['role'] == 'Security Guard') {
                header("Location: security_home.php");
            }
            exit;
        } else {
            $errorMsg = "Invalid password.";
        }
    } else {
        $errorMsg = "No user found with the provided details.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 30px; }
        .form-box { background: #fff; padding: 20px; border-radius: 10px; width: 350px; margin: auto; margin-bottom: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px; width: 100%; background: #28a745; color: white; border: none; border-radius: 5px; }
        button:hover { background: #218838; }
        .error { color: red; margin-top: 10px; }
        .success { color: green; margin-top: 10px; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Login</h2>
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

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Login</button>
    </form>
    <a href="reset_pwd.php">Reset password</a>
</div>

</body>
</html>
