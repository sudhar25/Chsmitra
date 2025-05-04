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
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CHSmitra - Reset Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(rgba(27, 63, 181, 0.6), rgba(0, 0, 86, 0.6)), url('images/Reset password.png') no-repeat center center fixed;
      background-size: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color:rgb(50, 55, 173);
    }

    .form-box {
  background: rgba(255, 255, 255, 0.35); /* More transparent */
  padding: 40px 30px;
  border-radius: 15px;
  box-shadow: 0 6px 20px rgba(0, 0, 80, 0.2);
  width: 100%;
  max-width: 400px;
  text-align: center;
  animation: fadeSlide 0.8s ease-out forwards;
  opacity: 0;
  transform: translateY(40px);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}



    @keyframes fadeSlide {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-box h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      margin-bottom: 20px;
    }

    input, select {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      background: #f9f9f9;
      font-size: 1rem;
    }

    input:focus, select:focus {
      border-color: #000056;
      background: #e6f2ff;
      outline: none;
    }

    button {
      padding: 14px 0;
      width: 100%;
      background: linear-gradient(135deg, #0077cc, #000056);
      color: white;
      font-size: 1.1rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background: linear-gradient(135deg, #000056, #002244);
      transform: translateY(-2px) scale(1.05);
    }

    .error {
      color: red;
      margin-top: 10px;
    }

    .success {
      color: green;
      margin-top: 10px;
    }

    @media (max-width: 768px) {
      .form-box {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="form-box">
    <h2>Reset Password</h2>
    <?php if (isset($errorMsg) && $errorMsg) echo "<p class='error'>$errorMsg</p>"; ?>
    <?php if (isset($successMsg) && $successMsg) echo "<p class='success'>$successMsg</p>"; ?>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Role:</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Member">Member</option>
            <option value="Security Guard">Security Guard</option>
        </select>

        <label>Society ID:</label>
        <input type="number" name="society_id" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" name="reset">Reset Password</button>
    </form>
</div>

</body>
</html>
