<?php
//session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password_input, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            echo "Login successful. Welcome, " . $_SESSION['name'];
            header("Location: dashboard.php"); // Redirect to dashboard
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }
}
?>

<!-- Login Form -->
<h2>Login</h2>
<form method="POST" action="">
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="login" value="Login">
</form>







<?php
session_start();
include 'db.php';

$successMsg = "";
$errorMsg = "";

// Handle Login
if (isset($_POST['login'])) {
    $user_id = intval($_POST['user_id']);
    $role = $_POST['role'];
    $password_input = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE user_id = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $role);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password_input, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header("Location: home.php");
            exit;
        } else {
            $errorMsg = "Invalid password.";
        }
    } else {
        $errorMsg = "No user found with that User ID and Role.";
    }
}

// Handle Password Reset using User ID
if (isset($_POST['reset'])) {
    $reset_user_id = intval($_POST['reset_user_id']);
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql_reset = "UPDATE Users SET password_hash = ? WHERE user_id = ?";
    $stmt_reset = $conn->prepare($sql_reset);
    $stmt_reset->bind_param("si", $hashed_password, $reset_user_id);

    if ($stmt_reset->execute()) {
        if ($stmt_reset->affected_rows > 0) {
            $successMsg = "Password reset successful!";
        } else {
            $errorMsg = "No account found with that User ID.";
        }
    } else {
        $errorMsg = "Error: " . $stmt_reset->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Reset Password</title>
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
        <label>User ID:</label>
        <input type="number" name="user_id" required><br>

        <label>Role:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Member">Member</option>
            <option value="Security Guard">Security Guard</option>
        </select><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Login</button>
    </form>
</div>

<div class="form-box">
    <h2>Reset Password</h2>
    <form method="POST">
        <label>User ID:</label>
        <input type="number" name="reset_user_id" required><br>

        <label>New Password:</label>
        <input type="password" name="new_password" required><br>

        <button type="submit" name="reset">Reset Password</button>
    </form>
</div>

</body>
</html>

