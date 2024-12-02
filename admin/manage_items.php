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
    <style>
        .add-new-item {
            background-color: #dc3545;
            border: none;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
        }
        
        .add-new-item:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3);
        }
        
        .add-new-item i {
            margin-right: 0.5rem;
        }
        
        .search-box {
            width: 100%;
            margin-bottom: 1rem;
        }
        
        .search-box .input-group {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .search-box input {
            border: 1px solid #ced4da;
            border-left: none;
            height: 45px;
            font-size: 16px;
        }
        
        .search-box .input-group-text {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-right: none;
            color: #6c757d;
        }
        
        .search-box input:focus {
            box-shadow: none;
            border-color: #dc3545;
        }
        
        #clearSearch {
            border: 1px solid #ced4da;
            border-left: none;
            background-color: #fff;
        }
        
        #clearSearch:hover {
            background-color: #f8f9fa;
        }
        
        #searchMessage {
            margin-top: 1rem;
        }
        
        .menu-item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .card-body {
                padding: 0.75rem;
            }
            
            .table td, .table th {
                padding: 0.75rem 0.5rem;
                vertical-align: middle;
            }
            
            .image-col {
                width: 60px;
                padding-left: 0.5rem !important;
            }
            
            .actions-col {
                width: 85px;
                padding-right: 0.5rem !important;
            }
            
            .menu-item-image {
                width: 45px;
                height: 45px;
            }
            
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
            }
            
            .item-name {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }
            
            .item-description {
                font-size: 0.8rem;
                color: #6c757d;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .mobile-optimized {
                margin: 0;
                width: 100%;
            }
            
            .add-new-item {
                width: 100%;
                margin-top: 1rem;
            }
            
            .search-box {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
                <?php include 'includes/admin_sidebar.php'; ?>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between mb-4">
                    <h1>Menu Items</h1>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="search-box">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bx bx-search"></i>
                                </span>
                                <input type="text" 
                                       id="searchInput" 
                                       class="form-control" 
                                       placeholder="Search items by name, description or price..."
                                       autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-danger add-new-item" data-bs-toggle="modal" data-bs-target="#addItemModal">
                            <i class="bx bx-plus"></i> Add New Item
                        </button>
                    </div>
                </div>
                <div id="searchMessage" class="alert alert-info" style="display: none;"></div>

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
                            <table class="table table-hover align-middle mobile-optimized">
                                <thead class="table-light">
                                    <tr>
                                        <th class="image-col">Image</th>
                                        <th>Name</th>
                                        <th class="d-none d-md-table-cell">Description</th>
                                        <th>Price</th>
                                        <th class="actions-col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="image-col">
                                            <img src="../<?php echo $item['image_path']; ?>" 
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                 class="menu-item-image rounded"
                                                 loading="lazy">
                                        </td>
                                        <td>
                                            <div class="item-name fw-medium"><?php echo htmlspecialchars(trim($item['name'])); ?></div>
                                            <div class="item-description d-md-none text-muted small">
                                                <?php echo substr(htmlspecialchars(trim($item['description'])), 0, 60) . '...'; ?>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell text-muted">
                                            <?php echo htmlspecialchars(trim(substr($item['description'], 0, 100))) . '...'; ?>
                                        </td>
                                        <td class="fw-medium">₱<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="actions-col">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/menu_management.js"></script>
    <script src="assets/js/admin.js"></script>
</body>
</html>