<?php
include '../db.php';

if (isset($_POST['register'])) {
    $society_id = $_POST['society_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO Users (society_id, name, email, password_hash, role, phone)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $society_id, $name, $email, $password, $role, $phone);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!-- Registration Form -->
<h2>Register User</h2>
<form method="POST" action="">
    Society ID: <input type="number" name="society_id" required><br><br>
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Role:
    <select name="role" required>
        <option value="Admin">Admin</option>
        <option value="Member">Member</option>
        <option value="Security Guard">Security Guard</option>
    </select><br><br>
    Phone: <input type="text" name="phone"><br><br>
    <input type="submit" name="register" value="Register">
</form>
