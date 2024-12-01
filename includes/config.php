<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sz_hotpot_haven');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");

// Constants
define('SITE_NAME', 'S&Z Hot Pot Haven');
define('SITE_URL', 'http://localhost/sz_hotpot_haven');
define('FACEBOOK_URL', 'https://www.facebook.com/profile.php?id=61559238920322');
?> 