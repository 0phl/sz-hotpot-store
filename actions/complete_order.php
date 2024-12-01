<?php
session_start();
require_once '../includes/config.php';

// Store order details in session
$_SESSION['order_completed'] = true;
$_SESSION['order_time'] = date('Y-m-d H:i:s');

if (isset($_SESSION['cart'])) {
    $_SESSION['last_order'] = $_SESSION['cart'];
}

// Return success response
header('Content-Type: application/json');
echo json_encode(['success' => true]);
