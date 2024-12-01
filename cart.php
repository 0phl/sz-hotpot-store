<?php
session_start();
require_once 'includes/config.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .btn {
                padding: 10px 15px;
                font-size: 14px;
            }
            
            .card {
                margin-bottom: 15px;
            }
            
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            #cart-items .card img {
                width: 100%;
                height: auto;
                aspect-ratio: 1;
            }
            
            .text-end {
                text-align: center !important;
            }
            
            .text-end .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            #customer-info .card-body {
                padding: 15px;
            }
            
            .input-group-sm > .form-control,
            .input-group-sm > .btn {
                padding: 0.25rem 0.5rem;
            }

            /* Invoice specific styles */
            #invoice-preview {
                margin: 0 -10px;
                border-radius: 0;
            }

            #invoice-preview .card-header {
                padding: 15px;
            }

            #invoice-preview .card-body {
                padding: 15px;
            }

            #invoice-content h3 {
                font-size: 1.5rem;
            }

            #invoice-content h5 {
                font-size: 1.1rem;
                margin-bottom: 15px;
            }

            #invoice-content .table {
                margin-bottom: 0;
            }

            #invoice-content .card {
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            #invoice-content .payment-instructions ol,
            #invoice-content .payment-instructions ul {
                padding-left: 1.2rem;
            }

            #invoice-content .payment-instructions li {
                margin-bottom: 8px;
            }

            #invoice-content .payment-instructions .card {
                background-color: #f8f9fa;
            }

            #invoice-content a {
                color: #dc3545;
                text-decoration: none;
            }

            #invoice-content a:hover {
                text-decoration: underline;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'includes/header.php'; ?>

    <main class="flex-grow-1">
        <div class="container py-5">
            <h2 class="mb-4">Your Shopping List</h2>
            
            <div id="cart-items">
                <!-- Cart items will be loaded here -->
            </div>

            <!-- Customer Information Form -->
            <div class="card mt-4" id="customer-info">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form id="customer-form">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Your Name*</label>
                            <input type="text" class="form-control" id="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone Number (Optional)</label>
                            <input type="tel" class="form-control" id="customer_phone">
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address (Optional)</label>
                            <textarea class="form-control" id="delivery_address" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Special Instructions (Optional)</label>
                            <textarea class="form-control" id="notes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoice Preview -->
            <div class="card mt-4" id="invoice-preview" style="display: none;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Summary</h5>
                    <button class="btn btn-outline-primary" onclick="captureInvoice()">
                        <i class="bx bx-camera"></i> Capture Invoice
                    </button>
                </div>
                <div class="card-body" id="invoice-content">
                    <!-- Invoice content will be generated here -->
                </div>
            </div>

            <div class="text-end mt-4">
                <h4>Total: ₱<span id="cart-total">0.00</span></h4>
                <button class="btn btn-secondary me-2" onclick="window.location.href='index.php#menu'">
                    Continue Shopping
                </button>
                <button class="btn btn-danger" id="generate-invoice-btn" onclick="generateInvoice()">
                    Generate Invoice
                </button>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/cart.js"></script>
</body>
</html>