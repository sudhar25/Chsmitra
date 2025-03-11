<?php
include 'db.php';

$sql = "SELECT DATABASE()";
$result = $conn->query($sql);

if ($result) {
    echo "Database Connected for chsmitra successfully!";
} else {
    echo "Error connecting to database: " . $conn->error;
}

$conn->close();
?>
