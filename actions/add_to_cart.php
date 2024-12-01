<?php
session_start();
require_once '../includes/config.php';

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

$item_id = intval($data['id']);
$quantity = intval($data['quantity'] ?? 1);

if ($quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit;
}

// Get item details from database
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update item in cart
if (isset($_SESSION['cart'][$item_id])) {
    $_SESSION['cart'][$item_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$item_id] = [
        'name' => $item['name'],
        'price' => $item['price'],
        'quantity' => $quantity,
        'image_path' => $item['image_path']
    ];
}

echo json_encode(['success' => true, 'message' => 'Item added to cart']);