<?php
require_once '../includes/config.php';

try {
    // Get all active menu items
    $sql = "SELECT * FROM items ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $items = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'items' => $items]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 