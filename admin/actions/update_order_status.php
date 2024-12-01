<?php
session_start();
require_once '../../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$order_id = intval($_POST['id']);
$status = $conn->real_escape_string($_POST['status']);

// Validate status
$valid_statuses = ['pending', 'completed', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

try {
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        // Send email notification to customer if status changes to completed
        if ($status === 'completed') {
            sendOrderStatusEmail($order_id);
        }
        
        echo json_encode(['success' => true]);
    } else {
        throw new Exception($conn->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function sendOrderStatusEmail($order_id) {
    global $conn;
    
    // Get order details
    $sql = "SELECT o.*, GROUP_CONCAT(i.name) as items 
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN items i ON oi.item_id = i.id
            WHERE o.id = ?
            GROUP BY o.id";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    
    if ($order) {
        $to = $order['customer_email'] ?? null;
        if ($to) {
            $subject = "Your Order #$order_id has been completed";
            $message = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Order Completed</h2>
                <p>Dear {$order['customer_name']},</p>
                <p>Your order #$order_id has been completed and is ready for delivery/pickup.</p>
                <p><strong>Order Details:</strong></p>
                <p>Items: {$order['items']}</p>
                <p>Total Amount: ₱" . number_format($order['total_amount'], 2) . "</p>
                <p>Thank you for choosing S&Z Hot Pot Haven!</p>
            </body>
            </html>";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: S&Z Hot Pot Haven <noreply@szhothaven.com>' . "\r\n";
            
            mail($to, $subject, $message, $headers);
        }
    }
} 