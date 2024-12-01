<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$items = [];
$total = 0;
foreach ($_SESSION['cart'] as $id => $cartItem) {
    // Get current item data from database
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    
    if ($item) {
        $subtotal = $item['price'] * $cartItem['quantity'];
        $total += $subtotal;
        $items[] = [
            'id' => $id,
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $cartItem['quantity'],
            'image_path' => $item['image_path'],
            'subtotal' => $subtotal
        ];
    }
}

echo json_encode([
    'success' => true,
    'items' => $items,
    'total' => $total
]); 