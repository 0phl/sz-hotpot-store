<?php
session_start();
require_once 'includes/config.php';

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Insert order
    $sql = "INSERT INTO orders (customer_name, customer_phone, delivery_address, notes, total_amount) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssd", 
        $data['customer_name'],
        $data['phone'],
        $data['address'],
        $data['notes'],
        $data['total']
    );
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Insert order items
    $sql = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    foreach ($data['items'] as $item) {
        $stmt->bind_param("iiid",
            $order_id,
            $item['id'],
            $item['quantity'],
            $item['price']
        );
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    // Send email notification to admin
    $to = "admin@szhothaven.com"; // Replace with actual admin email
    $subject = "New Order #" . $order_id;
    $message = createOrderEmail($data, $order_id);
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: S&Z Hot Pot Haven <noreply@szhothaven.com>' . "\r\n";

    mail($to, $subject, $message, $headers);

    echo json_encode(['success' => true, 'order_id' => $order_id]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function createOrderEmail($data, $order_id) {
    $items_html = '';
    foreach ($data['items'] as $item) {
        $items_html .= "<tr>
            <td>{$item['name']}</td>
            <td>{$item['quantity']}</td>
            <td>₱" . number_format($item['price'], 2) . "</td>
            <td>₱" . number_format($item['price'] * $item['quantity'], 2) . "</td>
        </tr>";
    }

    return "
    <html>
    <body style='font-family: Arial, sans-serif;'>
        <h2>New Order #{$order_id}</h2>
        <h3>Customer Details:</h3>
        <p>
            <strong>Name:</strong> {$data['customer_name']}<br>
            <strong>Phone:</strong> {$data['phone']}<br>
            <strong>Address:</strong> {$data['address']}<br>
            <strong>Notes:</strong> {$data['notes']}
        </p>
        
        <h3>Order Details:</h3>
        <table style='width: 100%; border-collapse: collapse;'>
            <thead>
                <tr style='background-color: #f8f9fa;'>
                    <th style='padding: 8px; border: 1px solid #dee2e6;'>Item</th>
                    <th style='padding: 8px; border: 1px solid #dee2e6;'>Quantity</th>
                    <th style='padding: 8px; border: 1px solid #dee2e6;'>Price</th>
                    <th style='padding: 8px; border: 1px solid #dee2e6;'>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                {$items_html}
                <tr>
                    <td colspan='3' style='text-align: right; padding: 8px; border: 1px solid #dee2e6;'><strong>Total:</strong></td>
                    <td style='padding: 8px; border: 1px solid #dee2e6;'><strong>₱" . number_format($data['total'], 2) . "</strong></td>
                </tr>
            </tbody>
        </table>
    </body>
    </html>";
} 