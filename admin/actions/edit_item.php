<?php
session_start();
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = intval($_POST['item_id']);
    // Add length validation
    if (strlen($_POST['name']) > 100) { // Assuming 100 is your column's length
        $_SESSION['message'] = "Item name is too long. Maximum 100 characters allowed.";
        $_SESSION['message_type'] = "danger";
        header('Location: ../manage_items.php');
        exit;
    }
    $name = $conn->real_escape_string(trim($_POST['name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $price = floatval($_POST['price']);
    
    // Start with basic SQL query
    $sql = "UPDATE items SET name = ?, description = ?, price = ?";
    $types = "ssd"; // string, string, double
    $params = array($name, $description, $price);
    
    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../uploads/menu/";
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $file_name;
        $relative_path = "uploads/menu/" . $file_name;
        
        // Check if image file is valid
        $valid_extensions = array("jpg", "jpeg", "png", "gif");
        if (!in_array($file_extension, $valid_extensions)) {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $_SESSION['message_type'] = "danger";
            header('Location: ../manage_items.php');
            exit;
        }
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Get old image path to delete later
            $old_image_query = "SELECT image_path FROM items WHERE id = ?";
            $stmt = $conn->prepare($old_image_query);
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $old_image = $stmt->get_result()->fetch_assoc()['image_path'];
            
            // Add image path to update query
            $sql .= ", image_path = ?";
            $types .= "s";
            $params[] = $relative_path;
        }
    }
    
    // Complete the SQL query
    $sql .= " WHERE id = ?";
    $types .= "i";
    $params[] = $item_id;
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Item updated successfully!";
        $_SESSION['message_type'] = "success";
        
        // Delete old image if new image was uploaded
        if (isset($old_image) && file_exists("../../" . $old_image)) {
            unlink("../../" . $old_image);
        }
    } else {
        $_SESSION['message'] = "Error updating item: " . $conn->error;
        $_SESSION['message_type'] = "danger";
        // Delete uploaded image if database update fails
        if (isset($target_file) && file_exists($target_file)) {
            unlink($target_file);
        }
    }
    
    header('Location: ../manage_items.php');
    exit;
} 