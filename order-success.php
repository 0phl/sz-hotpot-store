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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Order Success - S&Z Hot Pot Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            -webkit-tap-highlight-color: transparent;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
            overflow-x: hidden;
        }

        .success-container {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        @media (max-width: 768px) {
            .success-container {
                width: calc(100% - 32px);
                padding: 20px;
                margin: 16px;
            }
        }

        .step-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }

        .step-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .messenger-btn-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }

        .messenger-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: #0084ff;
            color: white !important;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            font-size: 1rem;
            border: none;
            width: auto;
            min-width: 200px;
            max-width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .messenger-btn:hover {
            background: #0073e6;
            transform: translateY(-1px);
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            font-size: 1rem;
            min-width: 140px;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        /* High DPI / Retina specific fixes */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            * {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        }

        /* Device-specific fixes */
        @media screen and (width: 412px) and (height: 915px) { /* S20 Ultra specific */
            body {
                width: 100vw;
                overflow-x: hidden;
            }

            .success-container {
                width: calc(100% - 32px);
                margin: 16px;
                will-change: transform;
            }

            .step-item {
                will-change: transform;
            }

            .messenger-btn, .nav-btn {
                width: calc(100% - 32px);
                margin: 8px auto;
                padding: 14px 24px;
                will-change: transform;
            }

            /* Force hardware acceleration */
            * {
                -webkit-transform: translateZ(0);
                -moz-transform: translateZ(0);
                -ms-transform: translateZ(0);
                -o-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-font-smoothing: subpixel-antialiased;
            }
        }

        @media screen and (max-width: 390px) { /* iPhone 12 */
            .messenger-btn {
                border-radius: 12px;
                font-weight: 600;
                padding: 12px 20px;
                font-size: 0.95rem;
                margin: 12px auto;
                width: 100%;
            }
        }

        .header-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .success-icon-wrapper {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.2);
        }

        .success-icon {
            font-size: 48px;
            color: white;
        }

        .main-title {
            color: #2d3436;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .subtitle {
            color: #636e72;
            font-size: 1.1rem;
            margin-bottom: 24px;
            line-height: 1.4;
        }

        .section-title {
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 30px;
            font-size: 1.5rem;
        }

        .step-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-align: left;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .step-number {
            background-color: #dc3545;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
            font-size: 14px;
        }

        .step-content {
            flex: 1;
        }

        .step-content h5 {
            margin: 0 0 8px 0;
            font-size: 1.1rem;
        }

        .step-content p {
            margin: 0;
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .action-buttons {
            margin-top: 15px;
            display: flex;
            justify-content: flex-start;
        }

        .messenger-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: #0084ff;
            color: white !important;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            font-size: 1rem;
            border: none;
            width: auto;
            min-width: 200px;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .messenger-btn:hover {
            background: #0073e6;
            transform: translateY(-1px);
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            font-size: 1rem;
            min-width: 140px;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .home-btn {
            background: #6c757d;
            color: white !important;
        }

        .menu-btn {
            background: #28a745;
            color: white !important;
        }

        .home-btn:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .menu-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .social-links-section {
            text-align: center;
            padding: 30px 0;
            margin: 20px 0;
        }

        .social-links-section h5 {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .social-links-section p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .contact-item i {
            font-size: 24px;
            color: #dc3545;
        }

        .contact-item strong {
            display: block;
            margin-bottom: 5px;
            color: #2d3436;
        }

        .contact-item p {
            margin: 0;
            color: #636e72;
        }

        .social-buttons {
            margin-top: 15px;
        }

        @media (max-width: 576px) {
            .nav-btn, .messenger-btn {
                width: 100%;
                min-width: 0;
                padding: 12px 20px;
            }
            
            .nav-buttons {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0;
                margin: 0;
            }

            .page-wrapper {
                padding: 10px 0;
                min-height: auto;
            }

            .step-item {
                padding: 15px;
            }

            .messenger-btn, .facebook-btn {
                padding: 8px 16px;
                font-size: 0.9rem;
                min-width: 180px;
            }

            .nav-btn {
                padding: 8px 16px;
                font-size: 0.9rem;
                min-width: 140px;
            }

            .navigation-section {
                padding: 0 10px;
            }

            .step-content h5 {
                font-size: 1rem;
            }

            .step-content p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="container">
            <div class="success-container animate__animated animate__fadeIn">
                <div class="header-section">
                    <div class="success-icon-wrapper animate__animated animate__bounceIn">
                        <i class="fas fa-check-circle success-icon"></i>
                    </div>
                    <h1 class="main-title">Invoice Generated Successfully!</h1>
                    <p class="subtitle">Thank you for choosing S&Z Hot Pot Haven!</p>
                </div>

                <div class="steps-container">
                    <h4 class="section-title">Complete Your Order:</h4>
                    
                    <div class="step-item animate__animated animate__fadeInLeft" style="animation-delay: 0.2s">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <h5>Send Your Invoice</h5>
                            <p>Please send the invoice screenshot you just captured to our Facebook Messenger to complete your order.</p>
                            <div class="messenger-btn-wrapper">
                                <a href="https://m.me/61559238920322" target="_blank" class="messenger-btn">
                                    <i class="fab fa-facebook-messenger fa-lg"></i>
                                    Send Invoice via Messenger
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="step-item animate__animated animate__fadeInLeft" style="animation-delay: 0.4s">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <h5>Wait for Confirmation</h5>
                            <p>We will review your order and send you a confirmation message with payment instructions.</p>
                        </div>
                    </div>

                    <div class="step-item animate__animated animate__fadeInLeft" style="animation-delay: 0.6s">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <h5>Order Processing</h5>
                            <p>Once payment is confirmed, we will process your order and coordinate delivery details with you.</p>
                        </div>
                    </div>
                </div>

                <div class="social-links-section animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                    <h5>Stay Connected!</h5>
                    <p>Follow us on Facebook for updates, promos, and new menu items!</p>
                    <div class="social-buttons">
                        <a href="https://www.facebook.com/profile.php?id=61559238920322" target="_blank" class="facebook-btn">
                            <i class="fab fa-facebook-f"></i>
                            Follow on Facebook
                        </a>
                    </div>
                </div>

                <div class="nav-buttons animate__animated animate__fadeInUp" style="animation-delay: 1s">
                    <a href="index.php" class="nav-btn home-btn">
                        <i class="fas fa-home"></i>
                        Return to Homepage
                    </a>
                    <a href="index.php#menu" class="nav-btn menu-btn">
                        <i class="fas fa-utensils"></i>
                        Browse Menu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling to all links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
    </script>
</body>
</html>
