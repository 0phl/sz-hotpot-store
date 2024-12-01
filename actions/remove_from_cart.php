<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$item_id = intval($_POST['id']);

// Remove item from cart
if (isset($_SESSION['cart'][$item_id])) {
    unset($_SESSION['cart'][$item_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
}
