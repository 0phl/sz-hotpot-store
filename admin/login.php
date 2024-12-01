<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: index.php');
            exit;
        }
    }
    $error = "Invalid credentials";
}
?>

<?php
$page_title = "Admin Login";

// Use a different header for admin pages
require_once 'includes/header.php';
?>

<style>
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    margin: 0;
}

.admin-login-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    padding: 2rem 0;
}

.login-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 400px;
    padding: 2.5rem;
}

.login-card h2 {
    color: #333;
    font-weight: 600;
    margin-bottom: 1.8rem;
    text-align: center;
    font-size: 1.8rem;
}

.form-control {
    border-radius: 8px;
    padding: 0.8rem 1rem;
    border: 1.5px solid #eaeaea;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    box-shadow: 0 0 0 3px rgba(227, 24, 55, 0.1);
    border-color: #e31837;
    background: #ffffff;
}

.form-label {
    color: #555;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.btn-login {
    background-color: #e31837;
    border: none;
    padding: 0.9rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.btn-login:hover {
    background-color: #c41530;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(227, 24, 55, 0.15);
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1.5px solid #eaeaea;
    border-left: none;
    cursor: pointer;
    color: #666;
}

.input-group-text:hover {
    background-color: #eaeaea;
}

.alert-danger {
    background-color: #fff2f2;
    border-color: #ffdbdb;
    color: #dc3545;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}
</style>

<div class="admin-login-container">
    <div class="login-card">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100 text-white">Login</button>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.querySelector('.fa-eye');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>