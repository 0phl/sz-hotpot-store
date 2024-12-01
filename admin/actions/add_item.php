<?php
session_start();
require_once '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    
    // Handle image upload
    $target_dir = "../../uploads/menu/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
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
        $sql = "INSERT INTO items (name, description, price, image_path) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $price, $relative_path);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Item added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error adding item: " . $conn->error;
            $_SESSION['message_type'] = "danger";
            // Delete uploaded image if database insert fails
            unlink($target_file);
        }
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your file.";
        $_SESSION['message_type'] = "danger";
    }
    
    header('Location: ../manage_items.php');
    exit;
} 