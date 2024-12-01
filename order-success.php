<?php
session_start();
if (!isset($_SESSION['order_completed']) || $_SESSION['order_completed'] !== true) {
    header('Location: index.php');
    exit;
}
// Clear the cart and order completion flag
unset($_SESSION['cart']);
unset($_SESSION['order_completed']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - S&Z Hot Pot Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            padding: 30px;
        }
        .success-icon {
            color: #28a745;
            font-size: 80px;
            margin-bottom: 20px;
        }
        .social-links {
            margin-top: 30px;
        }
        .social-links a {
            margin: 0 10px;
            font-size: 24px;
            color: #dc3545;
        }
        .message-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <i class="fas fa-check-circle success-icon"></i>
            <h1 class="mb-4">Thank You for Your Order!</h1>
            
            <div class="message-box">
                <p class="lead">Your order has been successfully received.</p>
                <p>We will process your order and contact you shortly via your provided contact information.</p>
            </div>

            <div class="mb-4">
                <p>For any questions about your order, please contact us:</p>
                <a href="https://www.facebook.com/profile.php?id=61559238920322" target="_blank" class="btn btn-danger">
                    <i class="fab fa-facebook-messenger"></i> Message us on Facebook
                </a>
            </div>

            <div class="mt-4">
                <a href="index.php" class="btn btn-outline-danger">Return to Homepage</a>
            </div>

            <div class="social-links">
                <p>Follow us for updates and promotions:</p>
                <a href="https://www.facebook.com/profile.php?id=61559238920322" target="_blank" title="Follow us on Facebook">
                    <i class="fab fa-facebook"></i>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
