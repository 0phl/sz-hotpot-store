<?php
session_start();
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = intval($_POST['id']);
    
    // Get image path before deleting the record
    $image_query = "SELECT image_path FROM items WHERE id = ?";
    $stmt = $conn->prepare($image_query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $image_path = $stmt->get_result()->fetch_assoc()['image_path'];
    
    // Delete the record
    $sql = "DELETE FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    
    $response = array();
    if ($stmt->execute()) {
        // Delete the image file
        if (file_exists("../../" . $image_path)) {
            unlink("../../" . $image_path);
        }
        $response['success'] = true;
        $response['message'] = "Item deleted successfully!";
    } else {
        $response['success'] = false;
        $response['message'] = "Error deleting item: " . $conn->error;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} 