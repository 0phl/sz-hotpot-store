<?php
session_start();
require_once '../../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID not provided']);
    exit;
}

$order_id = intval($_GET['id']);

try {
    // Get order details
    $sql = "SELECT o.*, 
            GROUP_CONCAT(i.name) as item_names,
            GROUP_CONCAT(oi.quantity) as quantities,
            GROUP_CONCAT(oi.price) as prices
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN items i ON oi.item_id = i.id
            WHERE o.id = ?
            GROUP BY o.id";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Format items
    $names = explode(',', $order['item_names']);
    $quantities = explode(',', $order['quantities']);
    $prices = explode(',', $order['prices']);
    
    $items = [];
    for ($i = 0; $i < count($names); $i++) {
        $items[] = [
            'name' => $names[$i],
            'quantity' => $quantities[$i],
            'price' => $prices[$i]
        ];
    }
    
    unset($order['item_names'], $order['quantities'], $order['prices']);
    $order['items'] = $items;

    echo json_encode(['success' => true, 'order' => $order]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 