<?php
require_once '../includes/config.php';

// Check if admin already exists
$check = $conn->query("SELECT id FROM admin LIMIT 1");
if ($check->num_rows > 0) {
    die("Admin account already exists!");
}

// Create admin account
$username = 'Sydney';
$password = password_hash('09615008090Sydney27', PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Admin account created successfully!<br>";
    echo "Username: Sydney<br>";
    echo "Password: 09615008090Sydney27<br>";
} else {
    echo "Error creating admin account: " . $conn->error;
} 