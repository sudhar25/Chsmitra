<?php
$servername = "localhost";
$username = "root";  // Default user in XAMPP
$password = "";      // No password by default
$database = "SocietyManagement1";

// Create connection (checking)
$conn = new mysqli("localhost", "root", "", "SocietyManagement1", 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully"; // Uncomment to test connection
?>
