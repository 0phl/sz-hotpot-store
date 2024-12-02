<?php
if (!defined('SITE_NAME')) {
    require_once '../includes/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #dc3545;
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .logo-container {
            background: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            margin-right: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .logo-container:hover {
            transform: scale(1.05);
        }
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .brand-name {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin-left: 15px;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .logo-container {
                width: 40px;
                height: 40px;
                padding: 6px;
            }
            .brand-name {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <div class="logo-container">
                    <img src="../assets/images/logo.png" alt="<?php echo SITE_NAME; ?>">
                </div>
                <span class="brand-name"><?php echo SITE_NAME; ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link text-white" href="../index.php#menu">Menu</a>
                    <a class="nav-link text-white" href="../cart.php">Cart</a>
                    <a class="nav-link text-white" href="<?php echo FACEBOOK_URL; ?>" target="_blank">Follow Us</a>
                    <a class="nav-link text-white active" href="login.php">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>
