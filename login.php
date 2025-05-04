<?php
session_start();
include 'db.php'; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, name, password_hash, role FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $name, $password_hash, $role);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;

            // Redirect based on role
            if ($role === 'Admin') {
                header("Location: admin_home.php");
            } elseif ($role === 'Security Guard') {
                header("Location: security_home.php");
            } else {
                header("Location: user_home.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CHSmitra - Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      transition: all 0.3s ease;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(rgba(0, 0, 86, 0.5), rgba(0, 0, 86, 0.5)), url('images/login.png') no-repeat center center fixed;
      background-size: 75%;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.88);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 80, 0.15);
      width: 100%;
      max-width: 400px;
      text-align: center;
      animation: iosSlideUp 0.8s ease-out forwards;
      opacity: 0;
      transform: translateY(100px);
      position: relative;
    }

    @keyframes iosSlideUp {
      0% {
        opacity: 0;
        transform: translateY(100px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-container {
      position: absolute;
      top: -60px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: white;
      padding: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      display: flex;
      justify-content: center;
      align-items: center;
      transition: transform 0.3s ease;
    }

    .logo-container img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      border-radius: 50%;
    }

    .login-container h1 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: #000056;
      margin-bottom: 20px;
      margin-top: 60px;
    }

    .login-input {
      width: 100%;
      padding: 12px 15px;
      font-size: 1rem;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      background: #f9f9f9;
    }

    .login-input:focus {
      border-color: #000056;
      background: #e6f2ff;
      outline: none;
    }

    .login-btn {
      width: 100%;
      padding: 14px 0;
      font-size: 1.1rem;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #0077cc, #000056);
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .login-btn:hover {
      background: linear-gradient(135deg, #000056, #002244);
      transform: translateY(-2px) scale(1.05);
    }

    .reset-password {
      font-size: 0.9rem;
      color: #000056;
      text-decoration: none;
    }

    .reset-password:hover {
      text-decoration: underline;
      color: #001144;
    }

    .login-container a {
      display: inline-block;
      margin-top: 10px;
    }

    @media (max-width: 768px) {
      .login-container {
        padding: 30px;
      }

      .logo-container {
        width: 80px;
        height: 80px;
      }

      .login-container h1 {
        margin-top: 50px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container" data-aos="fade-up">
    <div class="logo-container">
      <img src="Images/logo.png" alt="CHSmitra Logo">
    </div>
    <h1>Login to CHSmitra</h1>
    <form action="#" method="post">
      <input type="email" name="email" class="login-input" placeholder="Email Address" required />
      <input type="password" name="password" class="login-input" placeholder="Password" required />
      <button type="submit" class="login-btn">Login</button>
    </form>
    <a href="reset_pwd.php" class="reset-password">Reset Password?</a>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>
</body>
</html>
<?php if ($error): ?>
  <div style="color:red; text-align:center; margin-bottom: 10px;"><?php echo $error; ?></div>
<?php endif; ?>