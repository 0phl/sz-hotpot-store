<?php
session_start();
require_once '../includes/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get all menu items
$sql = "SELECT * FROM items ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/admin-style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/admin_header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
                <?php include 'includes/admin_sidebar.php'; ?>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
                    <h1 class="h2 mb-0">Menu Items</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bx bx-plus"></i> Add New Item
                    </button>
                </div>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                        <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th style="width: 120px;">Price</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <img src="../<?php echo $item['image_path']; ?>" 
                                                 alt="<?php echo $item['name']; ?>"
                                                 class="rounded"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td class="fw-medium"><?php echo $item['name']; ?></td>
                                        <td class="text-muted"><?php echo substr($item['description'], 0, 100) . '...'; ?></td>
                                        <td class="fw-medium">₱<?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary edit-item" 
                                                        data-id="<?php echo $item['id']; ?>"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editItemModal">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-item"
                                                        data-id="<?php echo $item['id']; ?>">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Item Modal -->
    <?php include 'includes/add_item_modal.php'; ?>
    
    <!-- Edit Item Modal -->
    <?php include 'includes/edit_item_modal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/menu_management.js"></script>
    <script src="assets/js/admin.js"></script>
</body>
</html>