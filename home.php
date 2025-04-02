<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CHSmitra</title>
</head>
<body>
    <div col>
        <div row=1>
        
            <p>chsmitra is a good platform</P>
        </div>
        <div row=2>
            <p>this is the second column\
            </p>
        </div>
    </div>
    <h1>Welcome to CHSmitra</h1>
    <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHS Mitra - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Montserrat:wght@300;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8f9fa;
            color: #333;
            text-align: center;
        }
        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            background: url('https://source.unsplash.com/1600x900/?modern,architecture') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
        }
        .navbar {
            position: absolute;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(255, 255, 255, 0.9);
        }
        .navbar a {
            color: #333;
            text-decoration: none;
            font-size: 1.2rem;
            padding: 10px 20px;
        }
        .content {
            position: relative;
            text-align: center;
            color: #333;
            width: 90%;
            max-width: 800px;
            z-index: 1;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            font-weight: 500;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            font-size: 1rem;
            color: white;
            background: #0056b3;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-weight: 500;
            text-decoration: none;
        }
        .btn:hover {
            background: #004494;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="overlay"></div>
        <div class="navbar">
            <a href="#">CHS Mitra</a>
            <div>
                <a href="login.html">Login</a>
                <a href="register.html" class="btn">Register</a>
            </div>
        </div>
        <div class="content">
            <h1>WELCOME TO CHS MITRA</h1>
            <p>Register to proceed further. If already registered, log in.</p>
            <a href="register.html" class="btn">Register</a>
            <a href="login.html" class="btn">Login</a>
        </div>
    </div>
</body>
</html>